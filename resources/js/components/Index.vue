<template>
    <div>
        <div class="title-container">
            <h3 class="text-center">Welcome to book rental website</h3>
            <h5 class="text-center">This is the place where you can borrow books.</h5>
        </div>
        <div class="cards-container" v-if="!pageIsLoading">
            <div class="genres-container" v-for="genre in genres" :key="genre.id">
                <div class="genre-title-container">
                    <h4>{{ genre.name }}</h4>
                </div>
                <div class="books-container">
                    <div class="card" v-for="book in genre.books" :key="book.id">
                        <img class="card-img-top rounded" :src="book.cover_image" alt="Book cover image">
                        <div class="card-body">
                            <div class="title-description">
                                <h5 class="card-title">{{ book.title }} <span class="font-italic text-sm">by {{ book.author.name }}</span></h5>
                                <p class="card-text">{{ cropText(book.description, 200) }}</p>
                            </div>
                            <div class="actions">
                                <router-link :to="`/books/${book.id}`" class="btn btn-primary float-right">See details</router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <spinner style="margin: 3rem auto 0 auto; width: 100%;" v-if="pageIsLoading" />
    </div>
</template>

<script>
    import Spinner from "./partials/Spinner";
    import {
        cropText,
        swalNotification } from "./../libraries/my-libs";
    export default {
        components: {
            Spinner
        },
        data() {
            return {
                pageIsLoading: true,
                genres: [],
            };
        },
        methods: {
            loadHomePageBooks() {
                const route = `/get/books/homepage`;
                axios.get(route).then(response => {
                    this.pageIsLoading = false;
                    if (response.data.success) {
                        this.genres = response.data.genres;
                    }
                }).catch(error => {
                    this.pageIsLoading = false;
                });
            },
            cropText(value, limit) {
                return cropText(value, limit);
            }
        },
        mounted() {
            this.loadHomePageBooks();
        }
    }
</script>

<style scoped lang="scss">
    .title-container {
        margin-top: 3.5rem;
    }
    .book-actions {
        margin-left: 1rem;
    }
    .actions-container {
        display: flex;
        flex-direction: row;
    }
    .cards-container {
        width: 90%;
        margin: 2rem auto 0 auto;
        .genres-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            .genre-title-container {
                margin-top: 1.5rem;
                h4 {
                    font-size: 2rem;
                    font-style: italic;
                }
            }
            .books-container {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                margin-bottom: 2rem;
                .card {
                    max-width: 20rem;
                    margin-top: 1rem;
                    margin-left: 1rem;
                    box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 15px;
                    transition: box-shadow 0.2s ease-in-out;
                    .card-body {
                        display: flex;
                        flex-direction: column;
                    }
                    // cursor: pointer;
                    img {
                        height: 19rem;
                    }
                    .title-description {
                        height: 16rem;
                    }
                    .actions {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        .visibility-icon {
                            margin-left: 0.5rem;
                            cursor: pointer;
                            font-size: 1.5rem;
                        }
                    }
                }
                .card:hover {
                    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
                }
            }
        }
    }
    .pagination {
        margin: 1rem auto 0 auto;
    }

    @media (max-width: 481px) {
        .title-container {
            h3 {
                font-size: 1.2rem;
            }
            h5 {
                font-size: 0.95rem;
            }
        }
    }
</style>
