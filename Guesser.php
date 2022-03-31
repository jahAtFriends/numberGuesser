<?php
    session_start();
    
    /*Check if a live game is in session*/
    if (!isset($_SESSION["game"])) {
        $_SESSION["game"] = new NumberGuesser();
    }
    
    /*Reset the game when requested*/
    if (isset($_REQUEST["reset"]) AND $_REQUEST["reset"]=="true") {
        http_response_code(200);
        echo('{"guesses-left":20}');
        session_unset();
        session_destroy();
        exit();
    }
    
    /*Require a guess to be supplied*/
    if (!isset($_POST["guess"])) {
        http_response_code(400);
        echo("Guess not supplied");
        exit();
    }
    
    $game = $_SESSION["game"];
    /*Do not allow guesses after allotted guesses have been used
    Instead, inform of the correct answer*/
    if (!$game->hasGuessesRemaining()) {
        http_response_code(200);
        echo('{"guesses-left":0,"answer":'.$game->correctAnswer().'}');
        exit();
    }
    
    $discrim = $game->evaluateGuess($_POST["guess"]);
    $guessesLeft = $game->guessesRemaining();
    
    $output = array('guesses-left' => $guessesLeft, 'discriminant' => $discrim);
    http_response_code(200);
    echo(json_encode($output));

    /*NumberGuesser Class:
    * Chooses a random number and allots 20 guesses to find it
    */
    class NumberGuesser{
        private $secretNumber;
        private $guessesLeft;

        function __construct() {
            $this->secretNumber = rand(0, 1000000);
            $this->guessesLeft = 20;
        }
        
        /*Returns if more than zero guesses remain*/
        function hasGuessesRemaining():bool {
            return $this->guessesLeft > 0;
        }
        
        /*Returns the number of guesses left in the game*/
        function guessesRemaining():int {
            return $this->guessesLeft;
        }
        
        /*Returns a discriminant: -1 for a too-low guess, 1 for
         * a too-high guess, and 0 for a correct guess. Each time
         * this method decrements the remaining guesses. */
        function evaluateGuess($guess) {
            $this->guessesLeft--;
            return $guess <=> $this->secretNumber;
        }
        
        /*Retrieves the correct answer/secret Number*/
        function correctAnswer():int {
            return $this->secretNumber;
        }
    }


?>