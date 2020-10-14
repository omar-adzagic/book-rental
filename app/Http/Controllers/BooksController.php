<?php

namespace App\Http\Controllers;

use App\Helpers\FilesHelper;
use App\Helpers\ImagesHelper;
use App\Http\Requests\BookStoreValidation;
use App\Http\Requests\BookUpdateValidation;
use App\Models\Book;
use App\Models\BookRental;
use App\Models\Genre;
use Carbon\Carbon;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class BooksController extends Controller
{
    /**
     * If user is not authenticated this method returns all books with necessary relations.
     * IF user is authenticated and if 'my_books' GET parameter is present this method returns sorted and paginated books of authenticated user, otherwise it will return books of other users that are marked as 'active'.
     * Filters for'booksFilter' parameter will intercept and modify $query to filter books accordingly.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBooks()
    {
        $iRented = request()->booksfilter == 'i-rented';
        $myBooks = ! empty(request()->my_books);

        if (! Auth::check()) {
            $query = DB::table('books')
                ->join('authors', 'authors.id', '=', 'books.author_id')
                ->leftJoin('book_rental', 'books.id', '=', 'book_rental.book_id');
            if (request()->booksfilter == 'rented') {
                $query->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('book_rental')
                        ->whereRaw('book_rental.book_id = books.id');
                });
            }
            if (request()->booksfilter == 'non-rented') {
                $query->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('book_rental')
                        ->whereRaw('book_rental.book_id = books.id');
                });
            }
            $books = $query->select('books.id',
                'books.cover_image',
                'books.title',
                'books.description',
                'books.publication_date',
                'books.genre_id',
                'books.author_id',
                'books.active',
                'authors.name AS author_name')
                ->orderBy('id', 'DESC')
                ->paginate(4);
        } else {
            $query = DB::table('books')
                ->join('authors', 'authors.id', '=', 'books.author_id')
                ->leftJoin('book_rental', 'books.id', '=', 'book_rental.book_id');
            if (request()->booksfilter == 'rented') {
                $query->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('book_rental')
                        ->whereRaw('book_rental.book_id = books.id');
                });
            }
            if (request()->booksfilter == 'non-rented') {
                $query->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('book_rental')
                        ->whereRaw('book_rental.book_id = books.id');
                });
            }
            $books = $query->select('books.id',
                'books.cover_image',
                'books.title',
                'books.description',
                'books.publication_date',
                'books.genre_id',
                'books.author_id',
                'books.active',
                'authors.name AS author_name')
                ->when(!$myBooks, function ($query) {
                    return $query->where('books.user_id', '!=', Auth::user()->id)
                        ->where('active', 1);
                })
                ->when($myBooks, function ($query) {
                    return $query->where('books.user_id', Auth::user()->id);
                })
                ->when($iRented, function ($query) {
                    return $query->where('book_rental.tenant_id', Auth::user()->id);
                })
                ->orderBy('id', 'DESC')
                ->paginate(4);
        }
        $books->map(function($book) {
            $book->publication_date = Carbon::parse($book->publication_date)->format('d/m/Y');
            return $book;
        });
        $responseData = [
            'success' => true,
            'books' => $books,
        ];

        return response()->json($responseData, 200);
    }

    /**
     * Returns single book with necessary relations.
     * In case when book is marked as inactive it cannot be accessed by any other user except the owner.
     *
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Book $book)
    {
        if ($book->active == 0 && !Auth::user()->books->contains($book->id)) {
            abort(403);
        }
        $book->load('author', 'genre', 'user', 'bookRental', 'bookRental.tenant');
        $responseData = [
            'success' => true,
            'book' => $book
        ];

        return response()->json($responseData, 200);
    }

    /**
     * Method for storing new book and uploading cover image.
     * BookStoreValidation will validate POST request data.
     *
     * @param BookStoreValidation $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BookStoreValidation $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;
        $validated['cover_image'] = ImagesHelper::uploadImage($validated['cover_image'], '/images/books/' . $validated['title'] . '/');
        Book::create($validated);
        $responseData = [
            'success' => true,
            'message' => 'The book has been successfully added.',
        ];

        return response()->json($responseData, 201);
    }

    /**
     * Method for updating book information and possibly changing cover image of the book.
     *
     * @param BookUpdateValidation $request
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BookUpdateValidation $request, Book $book)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;
        if (! empty($validated['cover_image'])) {
            $destinationPath = '/images/books/' . FilesHelper::cleanTextForFolderName($book->title) . '/';
            FilesHelper::deleteFileIfExists($book->cover_image);
            $validated['cover_image'] = ImagesHelper::uploadImage($validated['cover_image'], $destinationPath);
        }
        $book->update($validated);
        $responseData = [
            'success' => true,
            'message' => 'The book info has been saved.',
        ];

        return response()->json($responseData, 200);
    }

    /**
     * This method is toggling books 'active' status.
     * Appropriate message is also generated based on boolean value of 'active' column.
     *
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleBookVisibility(Book $book)
    {
        $message = $book->active ? 'The book is now invisible on the website.' : 'The book is now visible on the website.';
        $book->active = ! $book->active;
        $book->save();
        $responseData = [
            'success' => true,
            'message' => $message,
        ];

        return response()->json($responseData, 200);
    }

    /**
     * This method creates new book rental for authenticated user if book is marked as 'active'.
     *
     * @param Request $request
     * @param Book $book
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function rentBook(Request $request, Book $book)
    {
        if (! $book->active) {
            return false;
        }
        BookRental::create([
            'from' => $request->from,
            'to' => $request->to,
            'tenant_id' => Auth::user()->id,
            'book_id' => $book->id,
        ]);
        $responseData = [
            'success' => true,
            'message' => 'You rented the book successfully.'
        ];

        return response()->json($responseData, 200);
    }

    /**
     * This method returns three randomly generated genres with at least one and maximum three books in them which are also in random order.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHomepageBooks()
    {
        $threeRandomGenres = Genre::with(['books' => function ($query) {
            return $query->where('active', 1)->inRandomOrder();
        }, 'books.author'])
            ->has('books', '>', 0)
            ->has('books', '<=', 3)
            ->inRandomOrder()
            ->limit(3)
            ->get();
        $responseData = [
            'success' => true,
            'genres' => $threeRandomGenres,
        ];

        return response()->json($responseData, 200);
    }
}
