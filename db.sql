CREATE TABLE accounts(
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL,
    Age INT NOT NULL,
    Address VARCHAR(200) NOT NULL,
    Password VARCHAR(100) NOT NULL,
    Role ENUM('User', 'Admin') NOT NULL
);

INSERT INTO accounts(Name, Age, Address, Password, Role) VALUES
('admin', 21, 'Binangonan, Rizal', 'admin', 'Admin');
('user', 21, 'Angono, Rizal', 'user', 'User'),
('user2', 21, 'Taytay, Rizal', 'user2', 'User');


CREATE TABLE candidates (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL, 
    Position VARCHAR(100) NOT NULL,
    PartyList VARCHAR(100) NOT NULL,
    VoteCount INT NOT NULL
);

-- STATUS | LIST OF WINNERS (INCLUDING TIE CANDIDATES)
SELECT *
FROM candidates c
JOIN (
    SELECT Position, MAX(VoteCount) AS MaxVotes
    FROM candidates
    GROUP BY Position
) m ON c.Position = m.Position AND c.VoteCount = m.MaxVotes;

SELECT * FROM candidates c JOIN (SELECT Position, MAX(VoteCount) AS MaxVotes FROM candidates GROUP BY Position) m ON c.Position = m.Position AND c.VoteCount = m.MaxVotes;

-- ADDITIONAL

ALTER TABLE accounts MODIFY COLUMN Username VARCHAR(255) NOT NULL UNIQUE;

INSERT INTO accounts(Name, Age, Address, Username, Password, Role) VALUES
('admin', 21, 'Binangonan, Rizal', 'admin', 'admin', 'Admin'),
('user', 21, 'Angono, Rizal', 'user', 'user', 'User');