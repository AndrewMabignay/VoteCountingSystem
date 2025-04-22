<?php 

session_start();

$accountID = $_SESSION['id'];
// echo $accountID;

if (isset($_POST['logout'])) {
    require_once '../function/Model.php';
    $logout = new Model();
    $logout->logout();
}

if (isset($_POST['vote'])) {
    $id = $_POST['id'];
    $voteCount = $_POST['voteCount'];

    require_once '../function/Model.php';
    $voteCountAdded = new Model();
    $voteCountAdded->setDatabaseTable('candidates');
    $voteCountAdded->updateCandidatesVote($id, $voteCount);
}

require_once '../function/Model.php';

$listCandidates = new Model();
$listCandidates->setDatabaseTable('candidates');
$data = $listCandidates->candidateList();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- CSS LINKS -->
    <link rel="stylesheet" href="../public/css/general.css">
    <link rel="stylesheet" href="../public/css/client/client.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script>
        const currentUserID = "<?php echo $accountID; ?>";
    </script>
</head>
<body>
    <div class="vote-container">
        <form action="standard.php" method="POST" class="logout">
            <h2>Voting</h2>
            <button type="submit" name="logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>        
    
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Party List</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $candidates): ?>
                        <tr>
                            <td><?php echo $candidates['Name'] ?></td>
                            <td><?php echo $candidates['Position'] ?></td>
                            <td><?php echo $candidates['PartyList'] ?></td>
                            <td>
                                <form action="standard.php" method="POST" class="vote-form" data-position="<?php echo $candidates['Position']; ?>">
                                    <input type="hidden" name="id" value="<?php echo $candidates['ID']; ?> ">    
                                    <input type="hidden" name="name" value="<?php echo $candidates['Name']; ?> ">    
                                    <input type="hidden" name="position" value="<?php echo $candidates['Position']; ?> ">    
                                    <input type="hidden" name="partyList" value="<?php echo $candidates['PartyList']; ?> ">    
                                    <input type="hidden" name="voteCount" value="<?php echo $candidates['VoteCount'] ?> ">    

                                    <button type="submit" name="vote">Vote</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>        

    

    
    <script src="../public/js/status.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const votedKey = "votedPositions_" + currentUserID;  // Generate unique key per user ID
            const votedPositions = JSON.parse(sessionStorage.getItem(votedKey)) || [];  // Retrieve previously voted positions

            document.querySelectorAll(".vote-form").forEach(function (form) {
                const position = form.getAttribute("data-position");
                const voteBtn = form.querySelector("button[name='vote']");

                // If the position is already voted by this user, disable the button
                if (votedPositions.includes(position)) {
                    voteBtn.disabled = true;
                    voteBtn.textContent = "Voted";
                }

                // On form submit, store the voted position in sessionStorage
                form.addEventListener("submit", function () {
                    if (!votedPositions.includes(position)) {
                        votedPositions.push(position);
                        sessionStorage.setItem(votedKey, JSON.stringify(votedPositions));
                    }
                });
            });
        });
    </script>


</body>
</html>