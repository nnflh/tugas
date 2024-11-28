<?php
session_start();

$questions = [
    [
        "question" => "Jika 3 buku harganya Rp30.000, berapa harga 5 buku?",
        "choices" => ["Rp45.000", "Rp50.000", "Rp55.000", "Rp60.000"],
        "correctAnswer" => 0
    ],
    [
        "question" => "Dua mobil menempuh jarak 150 km dalam waktu yang sama. Jika mobil A melaju dengan kecepatan 60 km/jam, berapa kecepatan mobil B?",
        "choices" => ["50 km/jam", "60 km/jam", "75 km/jam", "90 km/jam"],
        "correctAnswer" => 3
    ],
    [
        "question" => "Jika perbandingan usia A dan B adalah 4:5 dan usia A adalah 20 tahun, berapa usia B?",
        "choices" => ["25 tahun", "30 tahun", "35 tahun", "40 tahun"],
        "correctAnswer" => 1
    ],
    [
        "question" => "Dalam perbandingan 2:3, jika total bagian adalah 50, berapa bagian yang diperoleh masing-masing?",
        "choices" => ["20 dan 30", "25 dan 35", "15 dan 35", "10 dan 40"],
        "correctAnswer" => 0
    ],
    [
        "question" => "Jika 4 pensil harganya Rp12.000, berapa harga 10 pensil?",
        "choices" => ["Rp20.000", "Rp25.000", "Rp30.000", "Rp40.000"],
        "correctAnswer" => 2
    ],
    [
        "question" => "Jika 5 liter susu harganya Rp40.000, berapa harga 8 liter susu?",
        "choices" => ["Rp50.000", "Rp64.000", "Rp80.000", "Rp100.000"],
        "correctAnswer" => 1
    ],
    [
        "question" => "Dalam perbandingan 7:2, jika jumlah total bagian adalah 90, berapa bagian masing-masing?",
        "choices" => ["70 dan 20", "30 dan 60", "70 dan 18", "60 dan 30"],
        "correctAnswer" => 0
    ],
    [
        "question" => "Sebuah mobil dapat menempuh 120 km dalam 2 jam. Berapa jarak yang dapat ditempuh mobil tersebut dalam 5 jam?",
        "choices" => ["240 km", "300 km", "350 km", "400 km"],
        "correctAnswer" => 0
    ],
    [
        "question" => "Jika 6kg beras seharga Rp54.000, berapa harga 10kg beras?",
        "choices" => ["Rp90.000", "Rp100.000", "Rp110.000", "Rp120.000"],
        "correctAnswer" => 1
    ],
    [
        "question" => "Jika 4 buah apel seharga Rp8.000, berapa harga 10 buah apel?",
        "choices" => ["Rp18.000", "Rp20.000", "Rp22.000", "Rp25.000"],
        "correctAnswer" => 1
    ],
];



if (isset($_GET['reset'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}


if (!isset($_SESSION['name']) && !isset($_SESSION['nim'])) {
   
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['nim'] = $_POST['nim'];
       
        $_SESSION['questionOrder'] = array_keys($questions); 
        shuffle($_SESSION['questionOrder']); 
        $_SESSION['currentQuestion'] = 0;
        $_SESSION['correctAnswers'] = 0; 
        $_SESSION['userAnswers'] = []; 
        $_SESSION['shuffledChoices'] = []; 
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

if (isset($_SESSION['name']) && isset($_SESSION['nim'])) {
    $currentQuestionIndex = $_SESSION['questionOrder'][$_SESSION['currentQuestion']];
    
    
    if (!isset($_SESSION['shuffledChoices'][$currentQuestionIndex])) {
       
        $choices = $questions[$currentQuestionIndex]['choices'];
        
        $correctAnswer = $questions[$currentQuestionIndex]['correctAnswer'];
        
        $shuffledChoices = $choices;
        shuffle($shuffledChoices);
        
        $_SESSION['shuffledChoices'][$currentQuestionIndex] = $shuffledChoices;
       
        $_SESSION['shuffledCorrectAnswer'][$currentQuestionIndex] = array_search($choices[$correctAnswer], $shuffledChoices);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userAnswer = isset($_POST['answer']) ? intval($_POST['answer']) : -1;
        if ($userAnswer != -1) {
            
            if ($userAnswer == $_SESSION['shuffledCorrectAnswer'][$currentQuestionIndex]) {
                
                $_SESSION['correctAnswers']++;
            }
            $_SESSION['userAnswers'][$_SESSION['currentQuestion']] = $userAnswer;
        }

        
        if (count($_SESSION['userAnswers']) === count($questions)) {
           
            $correctAnswersCount = $_SESSION['correctAnswers'];
            $_SESSION['score'] = min($correctAnswersCount * 10, 100); 
            header("Location: " . $_SERVER['PHP_SELF'] . "?results=true");
            exit();
        }

        if (isset($_POST['next'])) {
            $_SESSION['currentQuestion']++;
        } elseif (isset($_POST['submit'])) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    
    if (isset($_GET['question'])) {
        $questionIndex = intval($_GET['question']);
        if ($questionIndex >= 0 && $questionIndex < count($_SESSION['questionOrder'])) {
            $_SESSION['currentQuestion'] = $questionIndex;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuis Pengetahuan Umum</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
body {
    font-family: 'Roboto', sans-serif;
    background-color: #d1cfe2; 
    color: #333; 
    margin: 0;
    padding: 0;
}

.container {
    max-width: 480px; 
    margin: 50px auto;
    background-color: #ffffff;
    padding: 25px; 
    border-radius: 10px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); 
    border: 2px solid #9cadce; 
}

h1 {
    text-align: center;
    color: #2b6cb0;
    font-size: 26px;
    margin-bottom: 20px;
}

.question {
    font-size: 18px;
    margin-bottom: 15px;
    font-weight: bold;
    color: #2d3748;
}

label {
    font-size: 16px;
    margin: 8px 0;
    display: block;
    padding: 12px;
    background-color: #f7fafc;
    border: 2px solid #cbd5e0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

label:hover {
    background-color: #ebf8ff;
    border-color: #3182ce;
}

input[type="radio"] {
    margin-right: 10px;
}

button {
    display: block;
    width: 100%;
    background-color: #2b6cb0;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 20px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

button:hover {
    background-color: #2c5282;
    transform: scale(1.02); 
}

button:active {
    background-color: #1e3f63; 
    transform: scale(1);
}

.restart-button {
    background-color: #e53e3e;
    margin-top: 15px;
}

.restart-button:hover {
    background-color: #c53030;
}

.result {
    margin-top: 30px;
    font-size: 20px;
    text-align: center;
    color: #2d3748;
}

.summary {
    text-align: center;
    margin-top: 20px;
    font-size: 16px;
}

.status-boxes {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(30px, 1fr));
    gap: 8px;
    margin-bottom: 20px;
}

.status-box {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: bold;
    color: #fff;
    border-radius: 3px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.status-box.active {
    background-color: #transparent;
    border: 3px solid #626f78;
}

.not-answered {
    background-color: #e67e22;
}

.answered {
    background-color: #48bb78;
}

.input-container input {
    width: calc(100% - 24px); 
    padding: 10px; 
    border: 2px solid #cbd5e0;
    border-radius: 6px; 
    font-size: 14px; 
    color: #2d3748; 
    background-color: #edf2f7; 
}

.input-container label {
    margin-bottom: 8px; 
    display: block; 
    font-size: 16px; 
    
}

.input-container {
    margin-bottom: 20px; 
}

.input-container input:focus {
    outline: none;
    border-color: #3182ce;
    background-color: #ebf8ff;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Kuis Seputar Pengetahuan Umum</h1>

        <?php if (!isset($_SESSION['name']) && !isset($_SESSION['nim'])): ?>
            <form method="post">
                <div class="input-container">
                    <label for="name">Nama:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="input-container">
                    <label for="nim">NIM:</label>
                    <input type="text" id="nim" name="nim" required>
                </div>
                <button type="submit">Mulai Kuis</button>
            </form>
        <?php elseif (isset($_GET['results'])): ?>
            <?php
            $score = isset($_SESSION['score']) ? $_SESSION['score'] : 0;
            $correctAnswers = isset($_SESSION['correctAnswers']) ? $_SESSION['correctAnswers'] : 0;
            $totalQuestions = count($questions);
            ?>
            <div class="summary">
                <p>Nilai Anda: <?= $score; ?> dari 100</p>
                <p>Jawaban Benar: <?= $correctAnswers; ?> dari <?= $totalQuestions; ?></p>
                <p><?= $score == 100 ? "Wow, kamu keren banget!!!" : ($score == 0 ? "Cukup bagus, disarankan lebih baik lagi!!" : "Sayang sekali, jawaban anda salah semua!!!!"); ?></p>
                <form method="get">
                    <button type="submit" name="reset" class="restart-button">Mulai Ulang Kuis</button>
                </form>
                <a href="home.html"><button>Kembali ke Home</button></a> <!-- Tombol kembali ke Home -->
            </div>
        <?php else: ?>
            <div class="status-boxes">
    <?php for ($i = 0; $i < count($questions); $i++): ?>
        <a href="?question=<?= $i; ?>">
            <div class="status-box <?= isset($_SESSION['userAnswers'][$i]) ? 'answered' : 'not-answered'; ?> <?= $_SESSION['currentQuestion'] == $i ? 'active' : ''; ?>">
                <?= $i + 1; ?>
            </div>
        </a>
    <?php endfor; ?>
</div>

<div class="question"><?= $questions[$_SESSION['questionOrder'][$_SESSION['currentQuestion']]]['question']; ?></div>
            <form method="post">
                <div>
                <?php foreach ($_SESSION['shuffledChoices'][$_SESSION['questionOrder'][$_SESSION['currentQuestion']]] as $choiceIndex => $choice): ?>
                    <label>
                        <input type="radio" name="answer" value="<?= $choiceIndex; ?>" <?= isset($_SESSION['userAnswers'][$_SESSION['currentQuestion']]) && $_SESSION['userAnswers'][$_SESSION['currentQuestion']] == $choiceIndex ? 'checked' : ''; ?>>
                        <?= $choice; ?>
                    </label>
                <?php endforeach; ?>
                </div>

                <?php if ($_SESSION['currentQuestion'] < count($questions) - 1): ?>
                    <button type="submit" name="next">Selanjutnya</button>
                <?php else: ?>
                    <button type="submit" name="submit">Submit</button>
                <?php endif; ?>
                <?php if ($_SESSION['currentQuestion'] > 0): ?>
                    <button type="submit" name="previous">Sebelumnya</button>
                <?php endif; ?>
            </form>
        <?php endif; ?>
        <a href="home.html"><button>Kembali ke Home</button></a> <!-- Tambahkan tombol kembali ke Home di sini -->
    </div>
</body>
</html>