<template>
    <div class="modal fade" id="edit-user-password-modal" tabindex="-1" role="dialog" aria-labelledby="edit-user-password-modal-label" aria-hidden="true" ref="editUserPasswordModalRef">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit-user-password-modal-label">Change my password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST"  @submit.prevent="changePassword">
                    <div class="modal-body">
                        <div class="form-group mx-2 mt-2">
                            <label for="password">Password *</label>
                            <input type="password"
                                   id="password"
                                   class="form-control"
                                   autocomplete="off"
                                   placeholder="Enter password"
                                   :class="{ 'border border-danger': userErrors.passwordErrorPresent }"
                                   v-model="userForm.password" />
                            <small class="text-danger" v-if="userErrors.passwordErrorPresent">
                                {{ userErrors.password }}
                            </small>
                        </div>
                        <div class="form-group mx-2 mt-2">
                            <label for="password_confirmation">Confirm password *</label>
                            <input type="password"
                                   id="password_confirmation"
                                   class="form-control"
                                   autocomplete="off"
                                   placeholder="Confirm password"
                                   :class="{ 'border border-danger': userErrors.passwordConfirmationErrorPresent }"
                                   v-model="userForm.password_confirmation" />
                            <small class="text-danger" v-if="userErrors.passwordConfirmationErrorPresent">
                                {{ userErrors.passwordConfirmation }}
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary submit-btn">
                            Save
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-show="updating"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</template>

<script>
    import { EventBus } from "../../libraries/event-bus";
    import {
        swalNotification,
        checkIfNotEmpty,
        appendFormDataInputsIfNotEmpty,
        setAllObjectPropertiesToBoolean,
        checkForSingleValidationErrorsNoTabs,
        convertToMysqlDateFormat
    } from '../../libraries/my-libs';
    export default {
        data() {
            return {
                updating: false,
                userForm: {
                    id: '',
                    password: '',
                    password_confirmation: '',
                },
                userErrors: {
                    password: "",
                    passwordErrorPresent: false,
                    passwordConfirmation: "",
                    passwordConfirmationErrorPresent: false,
                },
            };
        },
        methods: {
            fillUserInfo(user) {
                this.resetUserErrors();
                this.resetUserForm();
                this.fillUserForm(user);
                $('#edit-user-password-modal').modal('show');
            },
            changePassword() {
                this.updating = true;
                const formData = new FormData();
                const inputFieldsArray = ['id', 'password', 'password_confirmation'];
                appendFormDataInputsIfNotEmpty(formData, this.userForm, inputFieldsArray);
                const route = `/users/${this.userForm.id}/change-password`;
                axios.post(route, formData).then(response => {
                    this.updating = false;
                    if(response.data.success) {
                        $("#edit-user-password-modal").modal("hide");
                        EventBus.$emit('load-users');
                        swalNotification('success', response.data.message);
                    }
                }).catch(error => {
                    this.updating = false;
                    if(error.response.status === 422) {
                        this.checkForValidationErrors(error.response.data.errors);
                    }
                });
            },
            clearData() {
                this.resetUserForm();
                EventBus.$emit('load-users');
            },
            fillUserForm(user) {
                this.userForm.id = user.id;
                this.userForm.password = user.password;
                this.userForm.password_confirmation = user.password_confirmation;
            },
            resetUserForm() {
                this.userForm.id = '';
                this.userForm.password = '';
                this.userForm.password_confirmation = '';
            },
            resetUserErrors() {
                this.userErrors.password = '';
                this.userErrors.passwordErrorPresent = false;
                this.userErrors.passwordConfirmation = '';
                this.userErrors.passwordConfirmationErrorPresent = false;
            },
            checkForValidationErrors(errors) {
                this.resetUserErrors();
                const validationErrorFieldsArray = ['password_confirmation'];
                checkForSingleValidationErrorsNoTabs(errors, this.userErrors, validationErrorFieldsArray);
                if (errors.hasOwnProperty("password")) {
                    if (errors["password"][0] == 'Password confirmation failed.') {
                        this.userErrors.passwordConfirmation = errors["password"][0];
                        this.userErrors.passwordConfirmationErrorPresent = true;
                    } else {
                        this.userErrors.password = errors["password"][0];
                        this.userErrors.passwordErrorPresent = true;
                    }
                }
            },
        },
        mounted() {
            EventBus.$on('open-edit-user-password-modal', user => this.fillUserInfo(user));
            $(this.$refs.editUserPasswordModalRef).on("hidden.bs.modal", this.clearData);
        }
    }
</script>
<style scoped>
    .modal-body .invalid-tab {
        color: #dc3545;
        background-color: #F8D3D7;
    }
</style>
