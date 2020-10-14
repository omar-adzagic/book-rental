<template>
    <div class="modal fade" id="edit-book-modal" tabindex="-1" role="dialog" aria-labelledby="edit-book-modal-label" aria-hidden="true" ref="createAndEditFaqModalRef">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit-book-modal-label">Edit book</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST"  @submit.prevent="updateBook" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group mx-2 mt-2">
                            <label for="title">Title *</label>
                            <input id="title"
                                   class="form-control"
                                   placeholder="Enter book title"
                                   :class="{ 'border border-danger': bookErrors.titleErrorPresent }"
                                   v-model="bookForm.title" />
                            <small class="text-danger" v-if="bookErrors.titleErrorPresent">
                                {{ bookErrors.title }}
                            </small>
                        </div>
                        <div class="form-group mx-2 mt-2">
                            <label for="last_name">Description *</label>
                            <textarea id="last_name"
                                      class="form-control"
                                      cols="10"
                                      rows="5"
                                      placeholder="Enter book description"
                                      :class="{ 'border border-danger': bookErrors.descriptionErrorPresent }"
                                      v-model="bookForm.description"></textarea>
                            <small class="text-danger" v-if="bookErrors.descriptionErrorPresent">
                                {{ bookErrors.description }}
                            </small>
                        </div>
                        <div class="form-group mx-2 my-2">
                            <label>Cover image</label>
                            <div>
                                <img class="rounded"
                                     width="100"
                                     alt="Cover image"
                                     :src="checkIfNotEmpty(loadImage) ? loadImage : bookForm.cover_image">
                            </div>
                            <div class="custom-file mt-2">
                                <input type="file"
                                       class="custom-file-input"
                                       id="customFile"
                                       name="header_image"
                                       @change="loadImageFile"/>
                                <label class="custom-file-label"
                                       for="customFile"
                                       :class="{ 'border border-danger': bookErrors.coverImageErrorPresent }">Choose file</label>
                            </div>
                            <small class="text-danger" v-if="bookErrors.coverImageErrorPresent">
                                {{ bookErrors.coverImage }}
                            </small>
                        </div>
                        <div class="form-group mx-2 mt-2">
                            <label for="publication_date">Publication date *</label>
                            <div class="input-group" style="border-radius: 0.25rem;" :class="{ 'border border-danger': bookErrors.publicationDateErrorPresent }">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <flat-pickr class="form-control"
                                            placeholder="Pick publication date"
                                            name="date"
                                            :config="config"
                                            v-model="bookForm.publication_date">
                                </flat-pickr>
                            </div>
                            <small class="text-danger" v-if="bookErrors.publicationDateErrorPresent">
                                {{ bookErrors.publicationDate }}
                            </small>
                        </div>
                        <div class="form-group mx-2 mt-2">
                            <label for="genre_id">Genre *</label>
                            <select id="genre_id"
                                    class="form-control"
                                    v-model="bookForm.genre_id"
                                    :class="{ 'border border-danger': bookErrors.genreIdErrorPresent }">
                                <option selected disabled value="">Pick genre</option>
                                <option :value="genre.id" :key="genre.id" v-for="genre in genres">
                                    {{ genre.name }}
                                </option>
                            </select>
                            <small class="text-danger" v-if="bookErrors.genreIdErrorPresent">
                                {{ bookErrors.genreId }}
                            </small>
                        </div>
                        <div class="form-group mx-2 mt-2">
                            <label for="author_id">Author *</label>
                            <select id="author_id"
                                    class="form-control"
                                    v-model="bookForm.author_id"
                                    :class="{ 'border border-danger': bookErrors.authorIdErrorPresent }">
                                <option selected disabled value="">Pick author</option>
                                <option :value="author.id" :key="author.id" v-for="author in authors">
                                    {{ author.name }}
                                </option>
                            </select>
                            <small class="text-danger" v-if="bookErrors.authorIdErrorPresent">
                                {{ bookErrors.authorId }}
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
        convertToMysqlDateFormat, convertToDateTimeFormat
    } from "../../libraries/my-libs";

    export default {
        data() {
            return {
                updating: false,
                loadImage: '',
                genres: [],
                authors: [],
                config: {
                    wrap: true,
                    dateFormat: 'd/m/Y',
                    altFormat: 'd/m/Y',
                },
                bookForm: {
                    id: "",
                    title: "",
                    description: "",
                    cover_image: "",
                    cover_image_file: "",
                    publication_date: "",
                    genre_id: "",
                    author_id: "",
                },
                bookErrors: {
                    title: "",
                    titleErrorPresent: false,
                    description: "",
                    descriptionErrorPresent: false,
                    coverImage: "",
                    coverImageErrorPresent: false,
                    genreId: "",
                    genreIdErrorPresent: false,
                    authorId: "",
                    authorIdErrorPresent: false,
                    publicationDate: "",
                    publicationDateErrorPresent: false,
                },
            };
        },
        methods: {
            editBook(book) {
                this.resetBookErrors();
                this.resetBookForm();
                this.fillBookForm(book);
                $('#edit-book-modal').modal('show');
            },
            loadData() {
                let getGenresRoute = `/get/genres`;
                let getAuthorsRoute = `/get/authors`;
                const genresRequest = axios.get(getGenresRoute);
                const authorsRequest = axios.get(getAuthorsRoute);
                const requestsArray = [genresRequest, authorsRequest];
                axios.all(requestsArray).then(axios.spread((...responses) => {
                    // this.pageIsLoading = false;
                    this.genres = responses[0].data.genres;
                    this.authors = responses[1].data.authors;
                })).catch(errors => {
                    // this.pageIsLoading = false;
                })
            },
            updateBook() {
                this.updating = true;
                const formData = new FormData();
                const inputFieldsArray = ['id', 'title', 'description', 'genre_id', 'author_id'];
                appendFormDataInputs(formData, this.bookForm, inputFieldsArray);
                if (checkIfNotEmpty(this.bookForm.publication_date)) {
                    formData.append('publication_date', convertToMysqlDateFormat(this.bookForm.publication_date));
                }
                if (checkIfNotEmpty(this.bookForm.cover_image_file)) {
                    formData.append('cover_image', this.bookForm.cover_image_file);
                }
                const route = `/books/${this.bookForm.id}/update`;
                axios.post(route, formData).then(response => {
                    this.updating = false;
                    if(response.data.success) {
                        $("#edit-book-modal").modal("hide");
                        EventBus.$emit('load-my-books');
                        swalNotification('success', response.data.message);
                    }
                }).catch(error => {
                    this.updating = false;
                    if(error.response.status === 422) {
                        this.checkForValidationErrors(error.response.data.errors);
                    }
                });
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
                this.bookForm.title = '';
                this.bookForm.description = '';
                this.bookForm.publication_date = '';
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
            fillBookForm(book) {
                this.bookForm.id = book.id;
                this.bookForm.title = book.title;
                this.bookForm.description = book.description;
                this.bookForm.publication_date = book.publication_date;
                console.log(book.publication_date);
                this.bookForm.genre_id = book.genre_id;
                this.bookForm.author_id = book.author_id;
                this.bookForm.cover_image = book.cover_image;
            },
            checkForValidationErrors(errors) {
                this.resetBookErrors();
                const validationErrorFieldsArray = ['title', 'description', 'publication_date', 'genre_id', 'author_id', 'cover_image'];
                checkForSingleValidationErrorsNoTabs(errors, this.bookErrors, validationErrorFieldsArray);
            },
            checkIfNotEmpty(value) {
                return checkIfNotEmpty(value);
            }
        },
        mounted() {
            this.loadData();
            EventBus.$on('open-edit-book-modal', book => this.editBook(book));
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
