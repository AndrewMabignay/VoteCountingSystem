<?php
session_start();
if (!isset($_SESSION['id'])):
    header("Location: ../../auth/login.php");
    exit;
elseif ($_SESSION['role'] !== "User"):
    header("Location: ../../admin/adminPanel.php?page=dashboard");
    exit;
endif;

$accountID = $_SESSION['id'];

$positionLimits = [
    'President' => 1,
    'Senator' => 12,
    'Counselor' => 10
];

if (isset($_POST['logout'])) {
    require_once '../function/Model.php';
    $logout = new Model();
    $logout->logout();
}

require_once '../function/Model.php';

// Check if user has already voted for a candidate in the position
if (isset($_POST['vote'])) {
    $id = $_POST['id'];
    $voteCount = $_POST['voteCount'];
    $position = $_POST['position'];

    // Insert vote logic
    $voteCountAdded = new Model();
    $voteCountAdded->castVote($accountID, $id, $position, $voteCount);
}

// Get list of candidates
$listCandidates = new Model();
$listCandidates->setDatabaseTable('candidates');
$data = $listCandidates->candidateListTwo();

// Check how many votes the user has per position
$userVotesPerPosition = $listCandidates->getUserVotesPerPosition($accountID);

// Get already voted candidate IDs for the user
$votedCandidateIDs = $listCandidates->getUserVotedCandidateIDs($accountID);

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
</head>
<body>
    <div class="vote-container">
        <form action="standard.php" method="POST" class="logout">
            <h2>Candidate Selection</h2>
            <button type="submit" name="logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>

        <div class="table-wrapper">
            <?php foreach ($data as $positioning => $candidates): ?>
            <h2><?php echo $positioning ?></h2>
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
                    <?php foreach ($candidates as $rows): ?>
                        <?php
                            $position = $rows['Position'];
                            $userVotes = $userVotesPerPosition[$position] ?? 0;
                            $limit = $positionLimits[$position] ?? 1;
                            $isDisabled = $userVotes >= $limit || in_array($rows['ID'], $votedCandidateIDs);
                        ?>
                        <tr>
                            <td><?php echo $rows['Name']; ?></td>
                            <td><?php echo $rows['Position']; ?></td>
                            <td><?php echo $rows['PartyList']; ?></td>
                            <td>
                                <form action="standard.php" method="POST" class="vote-form" data-position="<?php echo $rows['Position']; ?>">
                                    <input type="hidden" name="id" value="<?php echo $rows['ID']; ?>">
                                    <input type="hidden" name="name" value="<?php echo $rows['Name']; ?>">
                                    <input type="hidden" name="position" value="<?php echo $rows['Position']; ?>">
                                    <input type="hidden" name="partyList" value="<?php echo $rows['PartyList']; ?>">
                                    <input type="hidden" name="voteCount" value="<?php echo $rows['VoteCount']; ?>">
                                    <button type="submit" name="vote" <?php echo $isDisabled ? 'disabled' : ''; ?>>
                                        <?php echo $isDisabled ? 'Voted' : 'Vote'; ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="../public/js/status.js"></script>

    <!-- <script>
        window.addEventListener("pageshow", function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script> -->
</body>
</html>
