<template>
    <div>
        <div class="main-container" v-if="!pageIsLoading">
            <div class="card-body img-container">
                <img class="card-img-top rounded" :src="book.cover_image" alt="Book cover image">
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ book.title }} <span class="font-italic text-sm">by {{ book.author.name }}</span></h5>
                <p class="card-text">Genre: {{ book.genre.name }}</p>
                <p class="card-text">{{ book.description }}</p>
                <p class="card-text">Published: {{ convertToDateTimeFormat(book.publication_date, 'DD/MM/YYYY') }}</p>
                <p class="card-text">Owner: {{ book.user.full_name }}</p>
                <button type="button"
                        class="btn btn-sm btn-success"
                        v-if="authUser.loggedIn && !book.my_book && !book.rented"
                        @click="openRentBookModal(book)">Rent the book</button>
                <div v-if="book.rented && !book.rented_by_me">
                    <i class="fas fa-book-reader text-warning"></i>
                    <span class="font-italic text-warning">
                        Rented by user {{ book.book_rental.tenant.full_name }} from {{ convertToDateTimeFormat(book.book_rental.from, 'DD/MM/YYYY') }} to {{ convertToDateTimeFormat(book.book_rental.to, 'DD/MM/YYYY') }}
                    </span>
                </div>
                <div v-if="book.rented_by_me">
                    <i class="fas fa-book-reader text-success"></i>
                    <span class="font-italic text-success">Rented by me</span>
                </div>
            </div>
        </div>
        <spinner style="margin-top: 3rem;" v-if="pageIsLoading" />
        <book-rent />
    </div>
</template>

<script>
    import BookRent from "./BookRent";
    import Spinner from "../partials/Spinner";
    import { EventBus } from "../../libraries/event-bus";
    import { convertToDateTimeFormat } from "../../libraries/my-libs";

    export default {
        components: {
            BookRent,
            Spinner
        },
        data() {
            return {
                pageIsLoading: true,
                authUser: {},
                book: {
                    author: {},
                    genre: {},
                },
            };
        },
        methods: {
            loadAuthUser(authUserObject) {
                this.authUser = authUserObject.authUser;
            },
            loadBook() {
                const route = `/get/books/${this.$route.params.id}`;
                axios.get(route).then(response => {
                    this.pageIsLoading = false;
                    if(response.data.success) {
                        this.book = response.data.book;
                    }
                }).catch(error => {
                    this.pageIsLoading = false;
                });
            },
            openRentBookModal(book) {
                EventBus.$emit('open-rent-book-modal', book);
            },
            convertToDateTimeFormat(stringDate, format) {
                return convertToDateTimeFormat(stringDate, format);
            }
        },
        created() {
            EventBus.$emit('load-auth-user');
        },
        mounted() {
            this.loadBook();
            EventBus.$on('auth-user', authUserObject => this.loadAuthUser(authUserObject));
            EventBus.$on('load-book', () => this.loadBook());
        }
    }
</script>

<style scoped>
    .main-container {
        width: 50%;
        margin: 1.5rem auto 0 auto;
        display: flex;
        flex-direction: row;
        border: 1px solid #eee;
        box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px,
                    rgba(0, 0, 0, 0.23) 0px 6px 6px;
    }
    .img-container {
        /*max-width: 20%;*/
        flex-basis: 100%;
        border-right: 1px dashed #ccc;
    }

    @media (max-width: 768px) {
        .main-container {
            width: 67%;
        }
    }
</style>
