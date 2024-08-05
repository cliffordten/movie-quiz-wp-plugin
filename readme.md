## Plugin for Movie Quiz

The Movie Quiz Plugin adds a movie quiz feature to your WordPress website. Administrators can manage quiz questions, and users can take quizzes and view their history.

== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/movie-quiz` directory, or install the plugin through the WordPress plugins screen directly or clone this repository and take note of the following:
   ```
        - You should have docker installed
        - run `docker compose up --build` to build the image
        - run `docker compose run --rm composer install` to install compose dependencies
        - visit the wordpress site at `localhost:8088`
    ```
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the [movie_quiz] shortcode to display the quiz on a page or post.
