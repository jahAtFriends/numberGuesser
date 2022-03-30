<!DOCTYPE html>

<html>
  <head>
    <script>
      function validateGuess() {
          alert("Hello!");
          let guess = document.forms["guess-entry"]["guess"];
          if(!(Number.isInteger(guess) && guess > 0 && guess < 1000000) {
              alert("Guess must be an integer between 0 and 1 Million!");
          }
          return false;
      }
    </script>
    <title> What's My Number?</title>
  </head>
  <body>
    <h1> Number Guessing Game!</h1>
    <br/>
    <p> I'm thinking of a number between 1 and 1 Million. You have a total of 20 guesses to find the answer!</p>
    
    <form name="guess-entry" action="?" method="post" onsubmit="return validateGuess()">
      <label for="guess"> Guess a number: </label>
      <input id="guess" name="guess"/>
      <button type="submit" id="guess-submit">Submit Guess</button>
    </form>
    
    <form action="?" method="get"><button type="submit" id="reset">Reset Game</button> <input type="hidden" name="reset" value="true" /></form>
      
    
    <?php
    session_start();
    
    //Reset the game on request
    if (isset($_GET["reset"]) AND $_GET["reset"]) {
        echo "New Game! Please enter a guess.";
        session_unset();
        session_destroy();
        exit();
    }
    
    //If a game hasn't been started, start one.
    if (!isset($_SESSION['game'])) {
        $game = new NumberGuesser();
        $_SESSION['game'] = $game;
    }
    
    //If no guess is submitted, inform about this. (Unlikely)
    if (!isset($_POST["guess"])) {
        echo("no guess");
        exit();
    }
    
    $currentGuess = $_POST["guess"];
    $game = $_SESSION['game'];
    
    if ($game->evaluateGuess($currentGuess) < 0) {
        echo "Too Low! You have ".$game->guessesRemaining()." guesses left";
    } else if ($game->evaluateGuess($currentGuess) > 0) {
        echo "Too High! You have ".$game->guessesRemaining()." guesses left";
    } else {
        echo "You win!";
        echo "<script> document.getElementById('guess-submit').disabled=true;</script>";
        session_unset();
        session_destroy();
        exit();
    }
    
    if (!$game->hasGuessesRemaining()) {
        echo("<br/> You lose :-(  The correct answer was ".$game->correctAnswer());
        echo "<script> document.getElementById('guess-submit').disabled=true;</script>";
        session_unset();
        session_destroy();
        exit();
    }
    
    class NumberGuesser{
        private $secretNumber;
        private $guessesLeft;

        function __construct() {
            $this->secretNumber = rand(0, 1000000);
            $this->guessesLeft = 20;
        }
        
        function hasGuessesRemaining():bool {
            return $this->guessesLeft > 0;
        }
        
        function guessesRemaining():int {
            return $this->guessesLeft;
        }
        
        function evaluateGuess($guess) {
            $this->guessesLeft--;
            return $guess <=> $this->secretNumber;
        }
        
        function correctAnswer():int {
            return $this->secretNumber;
        }
    }
    ?>
    
  </body>
</html>