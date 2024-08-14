<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use setasign\Fpdi\Fpdi;

class ReportController extends Controller
{
    /**
     * Generate Report in pdf format
     * Split PDF reports into specified durations
     */
    public function generateReport(Request $request)
    {
        $rules = [
            'student' => 'required',
            'to_date' => 'required',
            'from_date' => 'required',
            'split_interval' => 'required'
        ];

        $response = ['success' => false];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['data'] = $validator->messages();
            return response($response, 400);
        } else {
            $user = User::find($request->student);
            $pdf = FacadePdf::loadView('report.user_report', [
                'user' => $user,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
            ]);
            if ($request->split_interval > 5) {
                for ($i = 1; $i <= $request->split_interval / 5; $i++) {
                    $pdf = FacadePdf::loadView('report.user_report', [
                        'user' => $user,
                        'from_date' => $request->from_date,
                        'to_date' => $request->to_date,
                    ]);
                    $pdfPath = storage_path("app/public/report_pdf_{$i}.pdf");
                    $pdf->save($pdfPath);
                    $splitDuration = $request->split_interval;

                    $splitFiles[] = $this->splitPdf($pdfPath, $splitDuration);
                }
            } else {
                $pdf = FacadePdf::loadView('report.user_report', [
                    'user' => $user,
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
                ]);
                $pdfPath = storage_path('app/public/user_report.pdf');
                $pdf->save($pdfPath);
                $splitDuration = $request->split_interval;
                $splitFiles[] = $this->splitPdf($pdfPath, $splitDuration);
            }

            return response()->json(['success' => true,'files' => $splitFiles]);
        }
    }

    /**
     * Create the pdf and split page
     */
    protected function splitPdf($filePath, $splitDuration)
    {
        if (!file_exists($filePath)) {
            throw new \Exception("File not found: " . $filePath);
        }
        $pdf = new Fpdi();
        if (!file_exists($filePath)) {
            throw new \Exception("File not found: " . $filePath);
        }
        $totalPages = $pdf->setSourceFile($filePath);

        $numSplits = ceil($totalPages / $splitDuration);
        $splitFiles = [];

        for ($i = 0; $i < $numSplits; $i++) {
            $startPage = $i * $splitDuration;
            $endPage = min($startPage + $splitDuration, $totalPages);

            $newPdf = new Fpdi();
            $newPdf->setSourceFile($filePath);
            for ($page = $startPage; $page < $endPage; $page++) {
                $newPdf->AddPage();
                $templateId = $newPdf->ImportPage($page + 1);
                $newPdf->useTemplate($templateId);
            }

            $outputFile = 'report_' . ($i + 1) . '.pdf';
            $outputFilePath = storage_path('app/public/' . $outputFile);
            $newPdf->Output('F', $outputFilePath);
            $splitFiles = asset('storage/' . $outputFile);
        }

        return $splitFiles;
    }
}
