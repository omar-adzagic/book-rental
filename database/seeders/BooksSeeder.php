<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Book::insert([
            [
                'title' => 'The Da Vinci Code',
                'description' => 'While in Paris, Harvard symbologist Robert Langdon is awakened by a phone call in the dead of the night. The elderly curator of the Louvre has been murdered inside the museum, his body covered in baffling symbols. As Langdon and gifted French cryptologist Sophie Neveu sort through the bizarre riddles, they are stunned to discover a trail of clues hidden in the works of Leonardo da Vinci—clues visible for all to see and yet ingeniously disguised by the painter.',
                'cover_image' => '/images/books/the_da_vinci_code.jpg',
                'publication_date' => Carbon::parse('2006-03-28'),
                'active' => true,
                'user_id' => rand(1, User::count()),
                'author_id' => Author::DAN_BROWN,
                'genre_id' => Genre::MYSTERY,
                'created_at' => now(),
            ],
            [
                'title' => 'The Silent Patient',
                'description' => 'Alicia Berenson’s life is seemingly perfect. A famous painter married to an in-demand fashion photographer, she lives in a grand house with big windows overlooking a park in one of London’s most desirable areas. One evening her husband Gabriel returns home late from a fashion shoot, and Alicia shoots him five times in the face, and then never speaks another word.',
                'cover_image' => '/images/books/the_silent_patient.jpg',
                'publication_date' => Carbon::parse('2019-02-05'),
                'active' => true,
                'user_id' => rand(1, User::count()),
                'author_id' => Author::ALEX_MICHAELIDES,
                'genre_id' => Genre::MYSTERY,
                'created_at' => now(),
            ],
            [
                'title' => 'The Girl with the Dragon Tattoo',
                'description' => 'Harriet Vanger, a scion of one of Sweden\'s wealthiest families disappeared over forty years ago. All these years later, her aged uncle continues to seek the truth. He hires Mikael Blomkvist, a crusading journalist recently trapped by a libel conviction, to investigate. He is aided by the pierced and tattooed punk prodigy Lisbeth Salander. Together they tap into a vein of unfathomable iniquity and astonishing corruption.',
                'cover_image' => '/images/books/the_girl_with_the_dragon_tattoo.jpg',
                'publication_date' => Carbon::parse('2008-09-16'),
                'active' => true,
                'user_id' => rand(1, User::count()),
                'author_id' => Author::STIEG_LARSSON,
                'genre_id' => Genre::MYSTERY,
                'created_at' => now(),
            ],
            [
                'title' => 'Harry Potter and the Philosopher\'s Stone',
                'description' => 'Harry Potter thinks he is an ordinary boy - until he is rescued by an owl, taken to Hogwarts School of Witchcraft and Wizardry, learns to play Quidditch and does battle in a deadly duel. The Reason ... HARRY POTTER IS A WIZARD!',
                'cover_image' => '/images/books/harry_potter.jpg',
                'publication_date' => Carbon::parse('1997-06-30'),
                'active' => true,
                'user_id' => rand(1, User::count()),
                'author_id' => Author::J_K_ROWLING,
                'genre_id' => Genre::FANTASY,
                'created_at' => now(),
            ],
            [
                'title' => 'The Lord of The Rings',
                'description' => 'In ancient times the Rings of Power were crafted by the Elven-smiths, and Sauron, the Dark Lord, forged the One Ring, filling it with his own power so that he could rule all others. But the One Ring was taken from him, and though he sought it throughout Middle-earth, it remained lost to him. After many ages it fell by chance into the hands of the hobbit Bilbo Baggins.',
                'cover_image' => '/images/books/the_lord_of_the_rings.jpg',
                'publication_date' => Carbon::parse('1955-10-20'),
                'active' => true,
                'user_id' => rand(1, User::count()),
                'author_id' => Author::J_R_R_TOLKIEN,
                'genre_id' => Genre::FANTASY,
                'created_at' => now(),
            ],
            [
                'title' => 'Don Quixote',
                'description' => 'Don Quixote has become so entranced by reading chivalric romances that he determines to become a knight-errant himself. In the company of his faithful squire, Sancho Panza, his exploits blossom in all sorts of wonderful ways. While Quixote\'s fancy often leads him astray—he tilts at windmills, imagining them to be giants—Sancho acquires cunning and a certain sagacity. Sane madman and wise fool, they roam the world together, and together they have haunted readers\' imaginations for nearly four hundred years.',
                'cover_image' => '/images/books/don_quixote.jpg',
                'publication_date' => Carbon::parse('1605-01-01'),
                'active' => true,
                'user_id' => rand(1, User::count()),
                'author_id' => Author::MIGUEL_DE_CERVANTES,
                'genre_id' => Genre::NOVEL,
                'created_at' => now(),
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'description' => 'The unforgettable novel of a childhood in a sleepy Southern town and the crisis of conscience that rocked it, To Kill A Mockingbird became both an instant bestseller and a critical success when it was first published in 1960. It went on to win the Pulitzer Prize in 1961 and was later made into an Academy Award-winning film, also a classic.',
                'cover_image' => '/images/books/to_kill_a_mockingbird.jpg',
                'publication_date' => Carbon::parse('1960-06-11'),
                'active' => true,
                'user_id' => rand(1, User::count()),
                'author_id' => Author::HARPER_LEE,
                'genre_id' => Genre::NOVEL,
                'created_at' => now(),
            ],
            [
                'title' => 'The Catcher in the Rye',
                'description' => 'The hero-narrator of The Catcher in the Rye is an ancient child of sixteen, a native New Yorker named Holden Caulfield. Through circumstances that tend to preclude adult, secondhand description, he leaves his prep school in Pennsylvania and goes underground in New York City for three days. The boy himself is at once too simple and too complex for us to make any final comment about him or his story. Perhaps the safest thing we can say about Holden is that he was born in the world not just strongly attracted to beauty but, almost, hopelessly impaled on it. There are many voices in this novel: children\'s voices, adult voices, underground voices-but Holden\'s voice is the most eloquent of all. Transcending his own vernacular, yet remaining marvelously faithful to it, he issues a perfectly articulated cry of mixed pain and pleasure. However, like most lovers and clowns and poets of the higher orders, he keeps most of the pain to, and for, himself. The pleasure he gives away, or sets aside, with all his heart. It is there for the reader who can handle it to keep.',
                'cover_image' => '/images/books/the_catcher_in_the_rye.jpg',
                'publication_date' => Carbon::parse('1951-07-16'),
                'active' => true,
                'user_id' => rand(1, User::count()),
                'author_id' => Author::J_D_SALINGER,
                'genre_id' => Genre::NOVEL,
                'created_at' => now(),
            ],
            [
                'title' => 'Dune',
                'description' => 'Set on the desert planet Arrakis, Dune is the story of the boy Paul Atreides, heir to a noble family tasked with ruling an inhospitable world where the only thing of value is the “spice” melange, a drug capable of extending life and enhancing consciousness. Coveted across the known universe, melange is a prize worth killing for...',
                'cover_image' => '/images/books/dune.jpg',
                'publication_date' => Carbon::parse('2019-01-10'),
                'active' => true,
                'user_id' => rand(1, User::count()),
                'author_id' => Author::FRANK_HERBERT,
                'genre_id' => Genre::SCIENCE_FICTION,
                'created_at' => now(),
            ],
            [
                'title' => ' The Left Hand of Darkness',
                'description' => 'A groundbreaking work of science fiction, The Left Hand of Darkness tells the story of a lone human emissary to Winter, an alien world whose inhabitants can choose - and change - their gender. His goal is to facilitate Winter\'s inclusion in a growing intergalactic civilization. But to do so he must bridge the gulf between his own views and those of the completely dissimilar culture that he encounters.',
                'cover_image' => '/images/books/the_left_hand_of_darkness.jpg',
                'publication_date' => Carbon::parse('2000-07-01'),
                'active' => true,
                'user_id' => rand(1, User::count()),
                'author_id' => Author::URSULA_K_LE_GUIN,
                'genre_id' => Genre::SCIENCE_FICTION,
                'created_at' => now(),
            ],
            [
                'title' => 'The first days',
                'description' => 'Katie is driving to work one beautiful day when a dead man jumps into her car and tries to eat her.  That same morning, Jenni opens a bedroom door to find her husband devouring their toddler son.',
                'cover_image' => '/images/books/first_days.jpg',
                'publication_date' => Carbon::parse('2019-01-10'),
                'active' => true,
                'user_id' => rand(1, User::count()),
                'author_id' => Author::RHIANNON_FRATER,
                'genre_id' => Genre::HORROR,
                'created_at' => now(),
            ],
            [
                'title' => 'Dracula',
                'description' => 'A rich selection of background and source materials is provided in three areas: Contexts includes probable inspirations for Dracula in the earlier works of James Malcolm Rymer and Emily Gerard. Also included are a discussion of Stoker\'s working notes for the novel and "Dracula\'s Guest," the original opening chapter to Dracula. Reviews and Reactions reprints five early reviews of the novel. "Dramatic and Film Variations" focuses on theater and film adaptations of Dracula, two indications of the novel\'s unwavering appeal. David J. Skal, Gregory A. Waller, and Nina Auerbach offer their varied perspectives. Checklists of both dramatic and film adaptations are included.',
                'cover_image' => '/images/books/dracula.jpg',
                'publication_date' => Carbon::parse('1897-05-26'),
                'active' => true,
                'user_id' => rand(1, User::count()),
                'author_id' => Author::BRAM_STOKER,
                'genre_id' => Genre::HORROR,
                'created_at' => now(),
            ],
        ]);
    }
}
