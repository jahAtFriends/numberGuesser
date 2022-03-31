# Number Guessers

There are two Number Guesser Games here. The first is `numberGuesser.php` which is
as standalone php number guesser game. _all_ of the code for the game is contained
in the single php file and minimal javascript is used.

Conversely, the other two files `index.html` and `Guesser.php` demonstrate a php and
javascript pair that divides the work of processing guesses from displaying responses
in a usefule way. This is more complex for a small game like this, but it is a
paradigm which will scale much better in larger projects: servers should handle
computation when this needs to be hidden from view or would be confusing for a client
to process, and the client should handle the parsing of the server's response and
formatting the output for the user.

You will make use of this technique in your next project!
