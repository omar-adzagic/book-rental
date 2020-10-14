<template>
    <div>
        <div class="profile-wrapper">
            <div class="profile-container" v-show="!pageIsLoading">
                <div class="form-group mx-2 mt-2">
                    <span class="profile-info-titles">First name</span>
                    <span class="profile-info" id="first_name">{{ userForm.first_name }}</span>
                </div>
                <div class="form-group mx-2 mt-2">
                    <span class="profile-info-titles">Last name</span>
                    <span class="profile-info" id="last_name">{{ userForm.last_name }}</span>
                </div>
                <div class="form-group mx-2 mt-2">
                    <span class="profile-info-titles">Email</span>
                    <span class="profile-info" id="email">{{ userForm.email }}</span>
                </div>
                <div class="form-group mx-2 mt-2">
                    <span class="profile-info-titles">Registered</span>
                    <span class="profile-info" id="registered">{{ userForm.created_at }}</span>
                </div>
            </div>
            <div class="profile-actions" v-show="!pageIsLoading">
                <div class="action" @click="openEditProfileModal">
                    <i class="fas fa-edit"></i>
                    <span>Edit profile info</span>
                </div>
                <div class="action" @click="openEditChangePasswordModal">
                    <i class="fas fa-key"></i>
                    <span>Change password</span>
                </div>
            </div>
            <spinner v-show="pageIsLoading" />
        </div>
        <profile-edit />
        <profile-change-password />
    </div>
</template>

<script>
    import ProfileChangePassword from "./ProfileChangePassword";
    import ProfileEdit from "./ProfileEdit";
    import Spinner from "../partials/Spinner";
    import { EventBus } from "../../libraries/event-bus";
    import {
        convertToDateTimeFormat
    } from "../../libraries/my-libs";

    export default {
        components: {
            ProfileEdit,
            ProfileChangePassword,
            Spinner,
        },
        data() {
            return {
                pageIsLoading: true,
                authUser: {},
                userForm: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    created_at: '',
                }
            };
        },
        methods: {
            loadAuthUser(authUserObject) {
                this.pageIsLoading = false;
                this.authUser = authUserObject.authUser;
                this.userForm.first_name = this.authUser.first_name;
                this.userForm.last_name = this.authUser.last_name;
                this.userForm.email = this.authUser.email;
                this.userForm.created_at = convertToDateTimeFormat(this.authUser.created_at, 'DD/MM/YYYY');
            },
            openEditProfileModal() {
                EventBus.$emit('open-edit-profile-modal', this.authUser);
            },
            openEditChangePasswordModal() {
                EventBus.$emit('open-edit-user-password-modal', this.authUser);
            }
        },
        created() {
            EventBus.$emit('load-auth-user');
        },
        mounted() {
            EventBus.$on('auth-user', authUserObject => this.loadAuthUser(authUserObject))
        }
    }
</script>

<style scoped lang="scss">
    .profile-wrapper {
        width: 66%;
        margin: 3rem auto;
        display: flex;
        flex-direction: row;
        .profile-container {
            width: 67%;
            padding: 2rem 3rem;
            border: 1px solid #eee;
            border-radius: 5px;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 15px -3px,
            rgba(0, 0, 0, 0.05) 0px 4px 6px -2px;
            .profile-info-titles,
            .profile-info {
                display: block;
            }
            .profile-info-titles {
                font-weight: 600;
                font-size: 1.5rem;
            }
            .profile-info {
                font-size: 1.25rem;
            }
        }
        .profile-actions {
            width: 33%;
            display: flex;
            flex-direction: column;
            .action {
                padding: 1.5rem 2rem;
                border: 1px solid #eee;
                border-radius: 5px;
                cursor: pointer;
                color: #777;
                &:hover {
                    background-color: #eee;
                    border-color: #fff;
                }
            }
        }
    }

    @media (max-width: 768px) {
        .profile-wrapper {
            width: 90%;
            .profile-container {
                width: 60%;
            }
            .profile-actions {
                width: 40%;
            }
        }
    }
    @media (max-width: 481px) {
        .profile-wrapper {
            width: 100%;
            .profile-container {
                padding: 1rem 2rem;
                .profile-info-titles {
                    font-size: 1.25rem;
                }
                .profile-info {
                    font-size: 1rem;
                }
            }
            .profile-actions {
                .action {
                    font-size: 0.83rem;
                }
            }
        }
    }
</style>
