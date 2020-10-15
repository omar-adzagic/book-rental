<template>
    <div>
        <div class="website-container">
            <ul class="nav main-nav">
                <li class="nav-item">
                    <router-link class="nav-link" to="/">Homepage</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" to="/books">Books</router-link>
                </li>
                <li class="nav-item" v-if="user.loggedIn">
                    <router-link class="nav-link" to="/my-books">My books</router-link>
                </li>
                <li class="nav-item" v-if="user.loggedIn">
                    <router-link class="nav-link" to="/profile">My profile</router-link>
                </li>
                <li class="nav-item float-right" v-show="!pageIsLoading && user.loggedIn">
                    <a href="#" class="nav-link active" @click.prevent="logout">Logout</a>
                </li>
                <li class="nav-item float-right" v-show="!pageIsLoading && !user.loggedIn">
                    <a href="/login" class="nav-link">Login</a>
                </li>
                <li class="nav-item float-right" v-show="!pageIsLoading && !user.loggedIn">
                    <a href="/register" class="nav-link">Register</a>
                </li>
                <li class="nav-item float-right loader-list-item">
                    <div class="spinner-border text-muted" v-if="pageIsLoading"></div>
                </li>
            </ul>
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import { EventBus } from './libraries/event-bus.js';
    import {
        checkIfNotEmpty,
        setAllObjectPropertiesToBoolean
    } from './libraries/my-libs';
    export default {
        data() {
            return {
                pageIsLoading: true,
                user: {},
                isLogged: false,
                breadcrumbUrl: "",
                pageName: "",
                activeTab: {
                    homepage: false,
                    books: false,
                    myBooks: false,
                    profile: false,
                }
            };
        },
        methods: {
            loadUserInfo() {
                const route = `/get/user-info`;
                axios.get(route).then(response => {
                    if(response.data.success) {
                        this.pageIsLoading = false;
                        if (checkIfNotEmpty(response.data.user)) {
                            this.user = response.data.user;
                        }
                        this.isLogged = response.data.isLogged;
                        console.log(this.isLogged);
                        EventBus.$emit("auth-user", { authUser: this.user, isLogged: this.isLogged });
                    }
                }).catch(() => {
                    this.pageIsLoading = false;
                    // Swal.fire("Neuspješno!", "Došlo je do greške", "warning");
                });
            },
            setActiveTab(tab) {
                setAllObjectPropertiesToBoolean(this.activeTab, false);
                this.$set(this.activeTab, tab, true);
            },
            logout() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Loggin out!',
                    text: "Are you sure you want to logout?",
                    showCancelButton: true,
                    cancelButtonColor: '#5a6268',
                    confirmButtonColor: '#0069d9',
                    cancelButtonText: 'Close',
                    confirmButtonText: 'Logout'
                }).then((result) => {
                    if (result.value) {
                        axios.post(`/logout`).then(response => location.href = "/");
                    }
                });
            },
            checkIfNotEmpty(value) {
                return checkIfNotEmpty(value);
            }
        },
        watch: {
            user(newValue, oldValue) {
                this.user = newValue;
            }
        },
        mounted() {
            this.loadUserInfo();
            EventBus.$on("load-auth-user", () => this.loadUserInfo());
        }
    }
</script>

<style lang="scss">
    .router-link-exact-active.router-link-active {
        background-color: #eee;
        // color: #f5fafe;
        border-color: #d8d8d8;
    }
    .website-container {
        margin: 0 2rem 2rem 2rem;
    }
    .main-nav {
        border: 1px solid #ccc;
        border-top: none;
        border-bottom-left-radius: 7px;
        border-bottom-right-radius: 7px;
        .nav-item.loader-list-item {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        a {
            border: 1px solid transparent;
            transition: background-color 0.25s ease-in-out;
            border-top: none;
            border-bottom: none;
            border-bottom-left-radius: 7px;
            border-bottom-right-radius: 7px;
            padding: 1.5rem 3rem;
            &:hover {
                background-color: #f8f8f8;
            }
        }
    }

    @media (max-width: 869px) {
        .main-nav {
            flex-direction: column;
            text-align: center;
        }
    }
    @media (max-width: 768px) {
        .website-container {
            margin-left: 0;
            margin-right: 0;
        }
        .main-nav {
            flex-direction: column;
            .router-link-exact-active.router-link-active {
                border-top-left-radius: 7px;
                border-top-right-radius: 7px;
            }
        }
    }
</style>
