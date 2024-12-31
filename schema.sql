-- Championship Table
CREATE TABLE
    championship (
        "id" SERIAL PRIMARY KEY,
        "name" TEXT NOT NULL,
        "start_time" TIMESTAMPTZ NOT NULL,
        "end_time" TIMESTAMPTZ,
        "location" TEXT,
        "number_of_sets" INT NOT NULL,
        "points_per_set" INT NOT NULL,
        "category" VARCHAR(1) CHECK (category IN ('M', 'F', 'X')) NOT NULL,
        "min_players_for_team" INT NOT NULL,
        "max_players_for_team" INT,
        "min_teams" INT NOT NULL,
        "max_teams" INT,
        "min_players_for_gender" INT,
        "min_age" INT,
        "max_age" INT
    )
;

COMMENT ON COLUMN championship.category IS 'Category of the championship: ''M'' for men, ''F'' for women, ''X'' for mixed.'
;

-- Team Table
CREATE TABLE
    team ("id" SERIAL PRIMARY KEY, "name" TEXT NOT NULL)
;

-- Player Table
CREATE TABLE
    player (
        "id" SERIAL PRIMARY KEY,
        "name" TEXT NOT NULL,
        "birth_date" DATE NOT NULL,
        "gender" CHAR(1) CHECK (gender IN ('M', 'F')) NOT NULL,
        "height" DECIMAL(3, 2),
        "weight" DECIMAL(5, 2),
        "position" VARCHAR(50),
        "team_id" INT NOT NULL,
        FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE
    )
;

-- Championship Team Table
CREATE TABLE
    championship_team (
        "id" SERIAL PRIMARY KEY,
        "championship_id" INT NOT NULL,
        "team_id" INT NOT NULL,
        FOREIGN KEY (championship_id) REFERENCES championship (id) ON DELETE CASCADE,
        FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE
    )
;

-- Championship Player Table (to track players in a specific championship)
CREATE TABLE
    championship_player (
        "id" SERIAL PRIMARY KEY,
        "championship_id" INT NOT NULL,
        "player_id" INT NOT NULL,
        "team_id" INT NOT NULL,
        FOREIGN KEY (championship_id) REFERENCES championship (id) ON DELETE CASCADE,
        FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE,
        FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE
    )
;

-- Bracket Table
CREATE TABLE
    bracket (
        "id" SERIAL PRIMARY KEY,
        "championship_id" INT NOT NULL,
        "round_number" INT NOT NULL,
        FOREIGN KEY (championship_id) REFERENCES championship (id) ON DELETE CASCADE
    )
;

-- Match Table
CREATE TABLE
    MATCH (
        "id" SERIAL PRIMARY KEY,
        "start_time" TIMESTAMPTZ NOT NULL,
        "end_time" TIMESTAMPTZ,
        "location" TEXT NOT NULL,
        "championship_id" INT NOT NULL,
        "bracket_id" INT NOT NULL,
        "team1_id" INT NOT NULL,
        "team2_id" INT NOT NULL,
        "winner_id" INT,
        "is_draw" BOOLEAN DEFAULT FALSE,
        FOREIGN KEY (championship_id) REFERENCES championship (id) ON DELETE CASCADE,
        FOREIGN KEY (bracket_id) REFERENCES bracket (id) ON DELETE CASCADE,
        FOREIGN KEY (team1_id) REFERENCES team (id) ON DELETE CASCADE,
        FOREIGN KEY (team2_id) REFERENCES team (id) ON DELETE CASCADE,
        FOREIGN KEY (winner_id) REFERENCES team (id) ON DELETE CASCADE
    )
;

-- Set Table (added to represent the sets in each match)
CREATE TABLE
    match_set (
        "id" SERIAL PRIMARY KEY,
        "match_id" INT NOT NULL,
        "set_number" INT NOT NULL,
        "team1_points" INT NOT NULL,
        "team2_points" INT NOT NULL,
        FOREIGN KEY (match_id) REFERENCES MATCH (id) ON DELETE CASCADE
    )
;