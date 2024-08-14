<template>
    <div id="app">
        <div class="container">
            <header>
                <nav className="navbar navbar-expand-lg navbar-dark navbar-custom">
                    <div className="container">
                        <a className="navbar-brand" href="#"><img src='#' alt='logo' /></a>
                        <button className="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
                            <span className="navbar-toggler-icon"></span>
                        </button>
                        <div className="collapse navbar-collapse" id="main_nav">
                            <ul className="navbar-nav ms-auto navbar-ul">
                                <li className="nav-item active me-3">
                                    <router-link to="/" class="nav-link">Home</router-link>
                                </li>
                                <li className="nav-item">
                                    <router-link to="/create" class="nav-link">Create Student</router-link>
                                </li>
                                <li className="nav-item">
                                    <router-link to="/login" v-if="!isAuthenticated" class="nav-link">Login</router-link>
                                </li>
                                <li className="nav-item">
                                    <button v-if="isAuthenticated" @click="logout" class="nav-link">Logout</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>

            <body> <router-view></router-view></body>
        </div>


    </div>
</template>

<script>
import axios from 'axios';
import { useRouter } from 'vue-router';

export default {
    setup() {
        const router = useRouter();
        const isAuthenticated = (!!window.localStorage.getItem('token'));

        const logout = async () => {
            try {
                const token = localStorage.getItem('token');
                await axios.post('/api/logout', {}, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                    },
                });
                localStorage.removeItem('token');
                router.push('/login').then(() => {
                    window.location.reload();
                });
            } catch (error) {
                console.error('Logout failed:', error);
            }
        };

        return { logout, isAuthenticated };
    }
};
</script>
<style scoped>
.nav-link {
    color: #0d6efd;
}
</style>
