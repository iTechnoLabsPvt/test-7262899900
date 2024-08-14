<?php

namespace App\Http\Controllers;

use App\Models\UploadedData;
use DateTime;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use ZipArchive;

class UploadController extends Controller
{
    /**
     * upload MS-Docx files.
     * Parse the uploaded files to extract and store the target improvement data for each student in the database.
     */
    public function upload(Request $request)
    {
        $rules = [
            'file' => 'required|mimes:docx'
        ];
        $response = ['success' => false];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['data'] = $validator->messages();
            return response($response, 400);
        } else {
            $file = $request->file('file');
            $path = $file->storeAs('uploads', 'document.docx');
            $fileName = time() . '_file.' . $file->extension();
            $file->move(public_path('/upload'), $fileName);
            $file = public_path('/upload') . '/' . $fileName;
            $zip = new ZipArchive();
            if ($zip->open($file) === TRUE) {
                $extractPath = public_path('/upload') . '/upload/extracted/';
                $zip->extractTo($extractPath);
                $zip->close();
            }
            $files = [
                'word/document.xml',
            ];
            $fullText = '';
            $arr = [];

            $data = [];
            foreach ($files as $xml) {
                $xmlFile = $extractPath . $xml;
                $xmlContent = file_get_contents($xmlFile);
                $dom = new DOMDocument();
                libxml_use_internal_errors(true);
                $dom->loadXML($xmlContent);


                $xpath = new DOMXPath($dom);
                $paragraphs = $xpath->query('//w:p');

                foreach ($paragraphs as $paragraph) {
                    $text = '';
                    foreach ($paragraph->childNodes as $node) {
                        if ($node->nodeType === XML_ELEMENT_NODE && $node->nodeName === 'w:r') {
                            foreach ($node->childNodes as $textNode) {
                                if ($textNode->nodeType === XML_ELEMENT_NODE && $textNode->nodeName === 'w:t') {
                                    $text .= $textNode->nodeValue . ' ';
                                    $data[] = $text;
                                }
                            }
                        }
                    }
                }


                foreach ($data as $index => $value) {
                    if (strpos($value, 'Start Date') !== false) {
                        if ($this->isValidDate(trim($data[$index + 1]))) {
                            $arr['Start Date'][] = trim($data[$index + 1]);
                        } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$index + 2])))) {
                            $arr['Start Date'][] = trim($data[$index + 2]);
                        } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$index + 3])))) {
                            $arr['Start Date'][] = str_replace(' ', '', trim($data[$index + 3]));
                        } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$index + 4])))) {
                            $arr['Start Date'][] = str_replace(' ', '', trim($data[$index + 4]));
                        }
                    } elseif (strpos($value, 'End Date') !== false) {
                        if ($this->isValidDate(trim($data[$index + 1]))) {
                            $arr['End Date'][] = trim($data[$index + 1]);
                        } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$index + 2])))) {
                            $arr['End Date'][] = trim($data[$index + 2]);
                        } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$index + 3])))) {
                            $arr['End Date'][] = str_replace(' ', '', trim($data[$index + 3]));
                        } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$index + 4])))) {
                            $arr['End Date'][] = str_replace(' ', '', trim($data[$index + 4]));
                        }
                    } elseif (strpos($value, 'Session  start  date ') !== false) {
                        if ($this->isValidDate(trim($data[$index + 1]))) {
                            $arr['Start Date'][] = trim($data[$index + 1]);
                        } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$index + 2])))) {
                            $arr['Start Date'][] = trim($data[$index + 2]);
                        } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$index + 3])))) {
                            $arr['Start Date'][] = str_replace(' ', '', trim($data[$index + 3]));
                        } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$index + 4])))) {
                            $arr['Start Date'][] = str_replace(' ', '', trim($data[$index + 4]));
                        }
                    } elseif (strpos($value, 'Session start date ') !== false) {
                        if ($this->isValidDate(trim($data[$index + 1]))) {
                            $arr['Start Date'][] = trim($data[$index + 1]);
                        } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$index + 2])))) {
                            $arr['Start Date'][] = trim($data[$index + 2]);
                        } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$index + 3])))) {
                            $arr['Start Date'][] = str_replace(' ', '', trim($data[$index + 3]));
                        } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$index + 4])))) {
                            $arr['Start Date'][] = str_replace(' ', '', trim($data[$index + 4]));
                        }
                    } elseif (strpos($value, 'Session end date ') !== false) {
                        if ($this->isValidDate(trim($data[$index + 1]))) {
                            $arr['End Date'][] = trim($data[$index + 1]);
                        } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$index + 2])))) {
                            $arr['End Date'][] = trim($data[$index + 2]);
                        } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$index + 3])))) {
                            $arr['End Date'][] = str_replace(' ', '', trim($data[$index + 3]));
                        } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$index + 4])))) {
                            $arr['End Date'][] = str_replace(' ', '', trim($data[$index + 4]));
                        }
                    } elseif (strpos($value, 'End Date ') !== false) {
                        if ($this->isValidDate(trim($data[$index + 1]))) {
                            $arr['End Date'] = trim($data[$index + 1]);
                        } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$index + 2])))) {
                            $arr['End Date'][] = trim($data[$index + 2]);
                        } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$index + 3])))) {
                            $arr['End Date'][] = str_replace(' ', '', trim($data[$index + 3]));
                        } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$index + 4])))) {
                            $arr['End Date'][] = str_replace(' ', '', trim($data[$index + 4]));
                        }
                    } elseif (strpos($value, 'target') !== false) {
                        $arr['Target'][] = trim($data[$index + 1]);
                    } elseif (strpos($value, 'Start From ') !== false) {
                        $maxCount = 2;
                        $count = 0;
                        for ($i = $index + 5; $i <= count($data); $i = $i + 5) {
                            if ($count >= $maxCount) {
                                break;
                            }
                            if ($this->isValidDate(trim($data[$i]))) {
                                $arr['Start Date'][] = trim($data[$i]);
                            }

                            $count++;
                        }
                    } elseif (strpos($value, 'End To ') !== false) {
                        $maxCount = 2;
                        $count = 0;
                        for ($i = $index + 5; $i <= count($data); $i = $i + 5) {
                            if ($count >= $maxCount) {
                                break;
                            }
                            if ($this->isValidDate(trim($data[$i]))) {
                                $arr['End Date'][] = trim($data[$i]);
                            }
                            $count++;
                        }
                    } elseif (strpos($value, 'Target ') !== false) {
                        $maxCount = 2;
                        $count = 0;
                        for ($i = $index + 5; $i <= count($data); $i = $i + 5) {
                            if ($count >= $maxCount) {
                                break;
                            }
                            if (is_numeric(trim($data[$i]))) {
                                $arr['Target'][] = trim($data[$i]);
                            }

                            $count++;
                        }
                    } elseif (strpos($value, 'Date ') !== false) {
                        $maxCount = 2;
                        $count = 0;
                        for ($i = $index + 7; $i <= count($data); $i = $i + 7) {
                            if ($count >= $maxCount) {
                                break;
                            }
                            if ($this->isValidDate(str_replace(' ', '', trim($data[$i])))) {
                                $startDate = trim($data[$i]);
                                $arr['Start Date'][] = str_replace(' ', '', trim($data[$i]));
                            } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$i + 1])))) {
                                $startDate = trim($data[$i + 1]);
                                $arr['Start Date'][] = str_replace(' ', '', trim($data[$i + 1]));
                            } elseif ($this->isValidDate(str_replace(' ', '', trim($data[$i + 2])))) {
                                $startDate = trim($data[$i + 2]);
                                $arr['Start Date'][] = str_replace(' ', '', trim($data[$i + 2]));
                            }
                            $endDate = str_replace($startDate . '  to ', '', trim($data[$i + 2]));
                            if ($this->isValidDate(str_replace(' ', '', $endDate))) {
                                $arr['End Date'][] = str_replace(' ', '', $endDate);
                            } else {
                                $endDate = str_replace($startDate . '  to ', '', trim($data[$i + 6]));
                                if ($this->isValidDate(str_replace(' ', '', $endDate))) {
                                    $arr['End Date'][] = str_replace(' ', '', $endDate);
                                }
                            }

                            $count++;
                        }
                        $tcount = 0;
                        for ($i = $index + 10; $i <= count($data); $i = $i + 10) {
                            if ($tcount >= $maxCount) {
                                break;
                            }
                            if (isset($data[$i])) {
                                $target = filter_var(trim($data[$i]), FILTER_SANITIZE_NUMBER_INT);

                                if (is_numeric($target)) {
                                    $arr['Target'][] = $target;
                                } else {
                                    if (isset($data[$i + 1])) {
                                        $target = filter_var(trim($data[$i + 1]), FILTER_SANITIZE_NUMBER_INT);
                                        if (is_numeric($target)) {
                                            $arr['Target'][] = $target;
                                        }
                                    }
                                }
                            }

                            $tcount++;
                        }
                    }
                }
            }
            if (isset($arr['Start Date'])) {
                foreach ($arr['Start Date'] as $key => $val) {
                    UploadedData::create([
                        'start_date' => $val,
                        'end_date' => str_replace(' ', '', $arr['End Date'][$key]),
                        'target' => str_replace(' ', '', $arr['Target'][$key]),
                        'user_id' => $request->user_id
                    ]);
                }
            }
            return response()->json(['success' => true, 'message' => 'File Uploaded Successfully'], 200);
        }
    }

    /**
     * Check the date format is valid or not
     */
    private function isValidDate($value, $format = 'Y-m-d')
    {
        $patterns = [
            'Y-m-d' => '/^\d{4}-\d{2}-\d{2}$/',
            'm/d/Y' => '/^\d{2}\/\d{2}\/\d{4}$/',
            'd/m/Y' => '/^\d{2}\/\d{2}\/\d{4}$/'
        ];

        if (isset($patterns[$format])) {
            return preg_match($patterns[$format], $value);
        }
    }
}
