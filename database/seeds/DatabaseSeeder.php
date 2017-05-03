<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         *
         * Seed for adding a personal user.
         *
         */
        DB::table('users')->insert([
            'first_name' => 'Pierre-Luc',
            'last_name' => 'Delisle',
            'email' => 'contact@pierre-luc-delisle.com',
            'address' => '111 rue de Touraine',
            'city' => 'Terrebonne',
            'province' => 'Quebec',
            'postal_code' => 'J6W6L9',
            'password' => bcrypt('secret'),
            'created_at' => '2017-02-19 15:00:00',
            'updated_at' => '2017-02-19 15:00:00',
        ]);

        /**
         *
         * Seed for adding a second personal user.
         *
         */
        DB::table('users')->insert([
            'first_name' => 'Steve',
            'last_name' => 'Jobs',
            'email' => 'steve.jobs@apple.com',
            'address' => '2101 Waverley Street',
            'city' => 'Palo Alto',
            'province' => 'California',
            'postal_code' => '94301',
            'password' => bcrypt('secret'),
            'created_at' => '2017-02-19 15:00:00',
            'updated_at' => '2017-02-19 15:00:00',
        ]);

        DB::table('users')->insert([
            'first_name' => 'Nicolas',
            'last_name' => 'Poitras',
            'email' => 'nicopoitras@gmail.com',
            'address' => '3511 Av du Parc',
            'city' => 'Montreal',
            'province' => 'Qc',
            'postal_code' => 'h2c3r5',
            'password' => bcrypt('secret'),
            'created_at' => '2017-02-19 15:00:00',
            'updated_at' => '2017-02-19 15:00:00',
        ]);

        DB::table('users')->insert([
            'first_name' => 'test',
            'last_name' => 'test',
            'email' => 'test@test.com',
            'address' => '3511 Av du Parc',
            'city' => 'Montreal',
            'province' => 'Qc',
            'postal_code' => 'h2c3r5',
            'password' => bcrypt('secret'),
            'created_at' => '2017-02-19 15:00:00',
            'updated_at' => '2017-02-19 15:00:00',
        ]);


        /**
         *
         * See for adding a user with no friends.
         *
         */
        DB::table('users')->insert([
            'first_name' => 'forever',
            'last_name' => 'alone',
            'email' => 'forever@alone.com',
            'address' => 'test',
            'city' => 'test',
            'province' => 'test',
            'postal_code' => 'test',
            'password' => bcrypt('secret'),
            'created_at' => '2017-02-19 15:00:00',
            'updated_at' => '2017-02-19 15:00:00',
        ]);

        /**
         *
         * Seed for generating 10 rand users.
         *
         */
        for( $i = 0; $i < 10 ; $i++)
        {
            DB::table('users')->insert([
                'first_name' => str_random(10),
                'last_name' => str_random(10),
                'email' => str_random(10).'@gmail.com',
                'address' => str_random(10),
                'city' => str_random(10),
                'province' => str_random(10),
                'postal_code' => str_random(6),
                'password' => bcrypt('secret'),
                'created_at' => '2017-02-19 15:00:00',
                'updated_at' => '2017-02-19 15:00:00',
            ]);
        }

        /**
         *
         * Seed for creating two particular tickets for one user.
         *
         */
        DB::table('tickets')->insert([
            'owner_first_name' => 'Pierre-Luc',
            'owner_last_name' => 'Delisle',
            'user_id' => '1',
            'event' => 'Adele - Live in Bell Center',
            'artist' => 'Adele Laurie Blue Adkins',
            'price' => '123.45',
            'venue' => 'Bell Center',
            'city' => 'Montreal',
            'description' => '25 is about getting to know who I’ve become without realising. And I’m sorry it took so long, but you know, life happened. Love, Adele',
            'date_event' => '2017-04-01 18:00:00',
            'image' => "http://www.billboard.com/files/styles/article_main_image/public/media/Adele-2015-press-Alasdair-McLellan-XL-billboard-650-2.jpg",
            'unique_id' => str_random(52),
            'created_at' => '2017-02-19 15:00:00',
            'updated_at' => '2017-02-19 15:00:00',
        ]);

        DB::table('tickets')->insert([
            'owner_first_name' => 'Pierre-Luc',
            'owner_last_name' => 'Delisle',
            'user_id' => '1',
            'event' => 'Adele - Live in Madison Square Garden',
            'artist' => 'Adele Laurie Blue Adkins',
            'price' => '123.45',
            'venue' => 'Madison Square Garden',
            'city' => 'New York City',
            'date_event' => '2017-04-18 18:00:00',
            'description' => '25 is about getting to know who I’ve become without realising. And I’m sorry it took so long, but you know, life happened. Love, Adele',
            'image' => "http://www.billboard.com/files/styles/article_main_image/public/media/Adele-2015-press-Alasdair-McLellan-XL-billboard-650-2.jpg",
            'unique_id' => str_random(52),
            'created_at' => '2017-02-19 15:00:00',
            'updated_at' => '2017-02-19 15:00:00',
        ]);

        DB::table('tickets')->insert([
            'owner_first_name' => 'Steve',
            'owner_last_name' => 'Jobs',
            'user_id' => '2',
            'event' => 'Imagine Dragon - Live in Madison Square Garden',
            'artist' => 'Imagine Dragon Blue Adkins',
            'price' => '89.45',
            'venue' => 'Madison Square Garden',
            'city' => 'New York City',
            'date_event' => '2017-05-08 18:00:00',
            'description' => '25 is about getting to know who I’ve become without realising. And I’m sorry it took so long, but you know, life happened. Love, Adele',
            'image' => "http://digitalspyuk.cdnds.net/14/15/1600x800/landscape_music-imagine-dragons.jpg",
            'unique_id' => str_random(52),
            'created_at' => '2017-02-19 15:00:00',
            'updated_at' => '2017-02-19 15:00:00',
        ]);

        DB::table('tickets')->insert([
            'owner_first_name' => 'Steve',
            'owner_last_name' => 'Jobs',
            'user_id' => '2',
            'event' => 'Imagine Dragon - Centre bell',
            'artist' => 'Imagine Dragon',
            'price' => '89.45',
            'venue' => 'Madison Square Garden',
            'city' => 'New York City',
            'date_event' => '2017-05-08 18:00:00',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'image' => "https://www.imaginedragonsmusic.com/sites/g/files/aaj1001/f/styles/suzuki_breakpoints_image_mobile-md_sq/public/release/201602/bb9a2da55ab31e91dbc47970e419195761a99caa_0.jpg?itok=5fnvpyPd",
            'unique_id' => str_random(52),
            'created_at' => '2017-02-19 15:00:00',
            'updated_at' => '2017-02-19 15:00:00',
        ]);

        DB::table('tickets')->insert([
            'owner_first_name' => 'Nicolas',
            'owner_last_name' => 'Poitras',
            'user_id' => '3',
            'event' => 'Backstreet boy - Centre Bell',
            'artist' => 'Backstreet boy',
            'price' => '220.99',
            'venue' => 'Madison Square Garden',
            'city' => 'New York City',
            'date_event' => '2017-07-15 18:00:00',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'image' => "http://ppcorn.com/us/wp-content/uploads/sites/14/2015/12/backstreet-boys-art-ppcorn.jpg",
            'unique_id' => str_random(52),
            'created_at' => '2017-02-19 15:00:00',
            'updated_at' => '2017-02-19 15:00:00',
        ]);




        /**
         *
         * Seed for generating 10 random tickets for random users.
         *
         */
        for( $i = 0; $i < 10 ; $i++)
        {
            DB::table('tickets')->insert([
                'owner_first_name' => str_random(10),
                'owner_last_name' => str_random(10),
                'user_id' => rand(1,12),
                'event' => str_random(10),
                'artist' => str_random(10),
                'price' => number_format((float)mt_rand(400,2000)/10.00, 2, '.', ''),
                'venue' => str_random(10),
                'city' => str_random(10),
                'description' => str_random(150),
                'date_event' => '2017-03-02 18:00:00',
                'unique_id' => str_random(52),
                'created_at' => '2017-02-19 15:00:00',
                'updated_at' => '2017-02-19 15:00:00',
            ]);
        }

    }
}
