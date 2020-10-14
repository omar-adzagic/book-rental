<template>
    <div class="modal fade" id="edit-profile-modal" tabindex="-1" role="dialog" aria-labelledby="edit-profile-modal-label" aria-hidden="true" ref="createAndEditFaqModalRef">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit-profile-modal-label">Edit my profile info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST"  @submit.prevent="updateProfile" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group mx-2 mt-2">
                            <label for="first_name">First name *</label>
                            <input id="first_name"
                                   class="form-control"
                                   placeholder="Enter first name"
                                   :class="{ 'border border-danger': userErrors.firstNameErrorPresent }"
                                   v-model="userForm.first_name" />
                            <small class="text-danger" v-if="userErrors.firstNameErrorPresent">
                                {{ userErrors.firstName }}
                            </small>
                        </div>
                        <div class="form-group mx-2 mt-2">
                            <label for="last_name">Last name *</label>
                            <input id="last_name"
                                   class="form-control"
                                   placeholder="Enter last name"
                                   :class="{ 'border border-danger': userErrors.lastNameErrorPresent }"
                                   v-model="userForm.last_name" />
                            <small class="text-danger" v-if="userErrors.lastNameErrorPresent">
                                {{ userErrors.lastName }}
                            </small>
                        </div>
                        <div class="form-group mx-2 mt-2">
                            <label for="email">Last name *</label>
                            <input id="email"
                                   class="form-control"
                                   placeholder="Enter email"
                                   :class="{ 'border border-danger': userErrors.emailErrorPresent }"
                                   v-model="userForm.email" />
                            <small class="text-danger" v-if="userErrors.emailErrorPresent">
                                {{ userErrors.email }}
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
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import { EventBus} from "../../libraries/event-bus";
    import {
        swalNotification,
        checkIfNotEmpty,
        appendFormDataInputs,
        setAllObjectPropertiesToBoolean,
        checkForSingleValidationErrorsNoTabs,
        convertToMysqlDateFormat
    } from "../../libraries/my-libs";

    export default {
        data() {
            return {
                updating: false,
                userForm: {
                    id: "",
                    first_name: "",
                    last_name: "",
                    email: "",
                },
                userErrors: {
                    firstName: "",
                    firstNameErrorPresent: false,
                    lastName: "",
                    lastNameErrorPresent: false,
                    email: "",
                    emailErrorPresent: false,
                },
            };
        },
        methods: {
            editUser(user) {
                this.resetUserErrors();
                this.resetUserForm();
                this.fillUser(user);
                $('#edit-profile-modal').modal('show');
            },
            updateProfile() {
                this.updating = true;
                const formData = new FormData();
                const inputFieldsArray = ['id', 'first_name', 'last_name', 'email'];
                appendFormDataInputs(formData, this.userForm, inputFieldsArray);
                const route = `/users/${this.userForm.id}/update`;
                axios.post(route, formData).then(response => {
                    this.updating = false;
                    if(response.data.success) {
                        $("#edit-profile-modal").modal("hide");
                        EventBus.$emit('load-auth-user');
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
            resetUserForm() {
                this.userForm.id = '';
                this.userForm.first_name = '';
                this.userForm.last_name = '';
                this.userForm.email = '';
            },
            resetUserErrors() {
                this.userErrors.firstName = '';
                this.userErrors.firstNameErrorPresent = false;
                this.userErrors.lastName = '';
                this.userErrors.lastNameErrorPresent = false;
                this.userErrors.email = '';
                this.userErrors.emailErrorPresent = false;
            },
            fillUser(user) {
                this.userForm.id = user.id;
                this.userForm.first_name = user.first_name;
                this.userForm.last_name = user.last_name;
                this.userForm.email = user.email;
            },
            checkForValidationErrors(errors) {
                this.resetUserErrors();
                const validationErrorFieldsArray = ['first_name', 'last_name', 'email'];
                checkForSingleValidationErrorsNoTabs(errors, this.userErrors, validationErrorFieldsArray);
            },
            checkIfNotEmpty(value) {
                return checkIfNotEmpty(value);
            }
        },
        mounted() {
            EventBus.$on('open-edit-profile-modal', user => this.editUser(user));
            $(this.$refs.createAndEditFaqModalRef).on("hidden.bs.modal", this.clearData);
        }
    }
</script>
<style scoped>
    .modal-body .invalid-tab {
        color: #dc3545;
        background-color: #F8D3D7;
    }
</style>
