<template>
    <div class="modal fade" id="rent-book-modal" tabindex="-1" role="dialog" aria-labelledby="rent-book-modal-label" aria-hidden="true" ref="createAndEditFaqModalRef">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rent-book-modal-label">Rent the book from user '{{ bookUser.full_name }}'</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST"  @submit.prevent="rentBook" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group mx-2 mt-2">
                            <label for="from">From date *</label>
                            <div class="input-group" style="border-radius: 0.25rem;" :class="{ 'border border-danger': bookErrors.fromErrorPresent }">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <flat-pickr class="form-control"
                                            placeholder="Pick from date"
                                            name="date"
                                            :config="configFrom"
                                            v-model="bookForm.from"
                                            @on-change="setToMinDate">
                                </flat-pickr>
                            </div>
                            <small class="text-danger" v-if="bookErrors.fromErrorPresent">
                                {{ bookErrors.from }}
                            </small>
                        </div>
                        <div class="form-group mx-2 mt-2">
                            <label for="to">To date *</label>
                            <div class="input-group" style="border-radius: 0.25rem;" :class="{ 'border border-danger': bookErrors.toErrorPresent }">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <flat-pickr class="form-control"
                                            placeholder="Pick to date"
                                            name="date"
                                            :config="configTo"
                                            v-model="bookForm.to"
                                            @on-change="setFromMaxDate">
                                </flat-pickr>
                            </div>
                            <small class="text-danger" v-if="bookErrors.toErrorPresent">
                                {{ bookErrors.to }}
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary submit-btn">
                            Rent the book
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-show="renting"></span>
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
                renting: false,
                loadImage: '',
                genres: [],
                configFrom: {
                    wrap: true,
                    dateFormat: 'd/m/Y',
                    altFormat: 'd/m/Y',
                },
                configTo: {
                    wrap: true,
                    dateFormat: 'd/m/Y',
                    altFormat: 'd/m/Y',
                },
                bookUser: {},
                bookForm: {
                    id: "",
                    from: "",
                    to: "",
                },
                bookErrors: {
                    from: "",
                    fromErrorPresent: false,
                    to: "",
                    toErrorPresent: false,
                },
            };
        },
        methods: {
            fillBook(book) {
                this.resetBookErrors();
                this.resetBookForm();
                this.bookForm.id = book.id;
                this.bookUser = book.user;
                $('#rent-book-modal').modal('show');
            },
            rentBook() {
                this.renting = true;
                const formData = new FormData();
                if (checkIfNotEmpty(this.bookForm.from)) {
                    formData.append('from', convertToMysqlDateFormat(this.bookForm.from));
                }
                if (checkIfNotEmpty(this.bookForm.to)) {
                    formData.append('to', convertToMysqlDateFormat(this.bookForm.to));
                }
                const route = `/books/${this.bookForm.id}/rent`;
                axios.post(route, formData).then(response => {
                    this.renting = false;
                    if(response.data.success) {
                        $("#rent-book-modal").modal("hide");
                        EventBus.$emit('load-book');
                        swalNotification('success', response.data.message);
                    }
                }).catch(error => {
                    this.renting = false;
                    if(error.response.status === 422) {
                        this.checkForValidationErrors(error.response.data.errors);
                    }
                });
            },
            setToMinDate(dates) {
                this.$set(this.configTo, 'minDate', dates[0]);
            },
            setFromMaxDate(dates) {
                this.$set(this.configFrom, 'maxDate', dates[0]);
            },
            loadImageFile(event) {
                let imageInput = event.target;
                if (imageInput.files && imageInput.files[0]) {
                    this.bookForm.cover_image_file = imageInput.files[0];
                    const reader = new FileReader();
                    reader.onload = evt => this.loadImage = evt.target.result;
                    reader.readAsDataURL(imageInput.files[0]);
                }
                imageInput.value = '';
            },
            clearData() {
                this.resetBookForm();
                EventBus.$emit('load-users');
            },
            resetBookForm() {
                this.bookForm.id = '';
                this.bookForm.from = '';
                this.bookForm.genre_id = '';
                this.bookForm.author_id = '';
                this.bookForm.cover_image = '';
                this.bookForm.cover_image_file = '';
                this.loadImage = '';
            },
            resetBookErrors() {
                this.bookErrors.title = '';
                this.bookErrors.titleErrorPresent = false;
                this.bookErrors.description = '';
                this.bookErrors.descriptionErrorPresent = false;
                this.bookErrors.publicationDate = '';
                this.bookErrors.publicationDateErrorPresent = false;
                this.bookErrors.genreId = '';
                this.bookErrors.genreIdErrorPresent = false;
                this.bookErrors.authorId = '';
                this.bookErrors.authorIdErrorPresent = false;
                this.bookErrors.coverImage = '';
                this.bookErrors.coverImageErrorPresent = false;
            },
            checkForValidationErrors(errors) {
                this.resetBookErrors();
                const validationErrorFieldsArray = ['title', 'description', 'from', 'genre_id', 'author_id', 'cover_image'];
                checkForSingleValidationErrorsNoTabs(errors, this.bookErrors, validationErrorFieldsArray);
            },
            checkIfNotEmpty(value) {
                return checkIfNotEmpty(value);
            }
        },
        mounted() {
            EventBus.$on('open-rent-book-modal', book => this.fillBook(book));
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
