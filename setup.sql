DROP TABLE IF EXISTS games;
DROP TABLE IF EXISTS types;

CREATE TABLE types (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL UNIQUE,
    image TEXT
);

CREATE TABLE games (
    id SERIAL PRIMARY KEY,
    image TEXT,
    type_id INTEGER NOT NULL REFERENCES types(id),
    title TEXT,
    description TEXT,
    info TEXT
);

INSERT INTO types (id, name, image) VALUES
(1, 'Action', '/images/cs2.jpg'),
(2, 'Adventure', '/images/detroit.jpeg'),
(3, 'Platformer', '/images/geometry_dash.jpeg'),
(4, 'Roguelike', '/images/inscryption.jpeg'),
(5, 'RPG', '/images/bg3.jpeg'),
(6, 'Sandbox', '/images/minecraft.jpeg'),
(7, 'Simulation', '/images/factorio.jpeg'),
(8, 'Strategy', '/images/hoi4.jpeg');

SELECT setval(pg_get_serial_sequence('types', 'id'), (SELECT MAX(id) FROM types));

INSERT INTO games (image, type_id, title, description, info) VALUES
('/images/bg3.jpeg', 5, 'Baldur''s Gate 3', 'A rich, story-driven party-based RPG set in the universe of Dungeons & Dragons.', 'Developer: Larian Studios | Genre: RPG | Released: 2023'),
('/images/civilization.jpeg', 8, 'Sid Meier''s Civilization VI', 'A turn-based strategy 4X game where you attempt to build an empire to stand the test of time.', 'Developer: Firaxis Games | Genre: 4X Strategy | Released: 2016'),
('/images/clone_drone.jpg', 1, 'Clone Drone in the Danger Zone', 'A physics-based voxel sword fighter where your mind is downloaded into a robot gladiator.', 'Developer: Doborog Games | Genre: Action / Beat ''em up | Released: 2021'),
('/images/cs2.jpg', 1, 'Counter-Strike 2', 'A tactical first-person shooter focusing on competitive 5v5 bomb defusal gameplay.', 'Developer: Valve | Genre: FPS | Released: 2023'),
('/images/cyberpunk.jpeg', 5, 'Cyberpunk 2077', 'An open-world, action-adventure story set in Night City, a megalopolis obsessed with power and glamour.', 'Developer: CD Projekt Red | Genre: Action RPG | Released: 2020'),
('/images/detroit.jpeg', 2, 'Detroit: Become Human', 'A choice-driven narrative adventure following three androids as they navigate a futuristic world.', 'Developer: Quantic Dream | Genre: Adventure | Released: 2018'),
('/images/elden_ring.jpg', 5, 'Elden Ring', 'A challenging action RPG featuring a vast open world created in collaboration with George R.R. Martin.', 'Developer: FromSoftware | Genre: Action RPG | Released: 2022'),
('/images/factorio.jpeg', 7, 'Factorio', 'A game about building and maintaining automated factories to produce items of increasing complexity.', 'Developer: Wube Software | Genre: Simulation / Strategy | Released: 2020'),
('/images/geometry_dash.jpeg', 3, 'Geometry Dash', 'A rhythm-based platformer game where you jump, fly, and flip your way through dangerous passages.', 'Developer: RobTop Games | Genre: Rhythm Platformer | Released: 2013'),
('/images/hoi4.jpeg', 8, 'Hearts of Iron IV', 'A grand strategy wargame focused on World War II, where players control any nation.', 'Developer: Paradox Development Studio | Genre: Grand Strategy | Released: 2016'),
('/images/inscryption.jpeg', 4, 'Inscryption', 'An inky black card-based odyssey that blends deckbuilding roguelikes, escape-room puzzles, and psychological horror.', 'Developer: Daniel Mullins Games | Genre: Roguelike Deckbuilder | Released: 2021'),
('/images/minecraft.jpeg', 6, 'Minecraft', 'A 3D sandbox game with infinite terrain generation where players build and survive.', 'Developer: Mojang Studios | Genre: Sandbox | Released: 2011'),
('/images/stardew.jpeg', 7, 'Stardew Valley', 'A cozy, open-ended country-life RPG where you inherit your grandfather''s old farm plot.', 'Developer: ConcernedApe | Genre: Simulation | Released: 2016'),
('/images/terr.jpeg', 6, 'Terraria', 'A 2D sandbox adventure game where players can dig, fight, explore, and build.', 'Developer: Re-Logic | Genre: Sandbox | Released: 2011'),
('/images/witcher3.jpeg', 5, 'The Witcher 3: Wild Hunt', 'A story-driven open-world RPG set in a visually stunning dark fantasy universe.', 'Developer: CD Projekt Red | Genre: Action RPG | Released: 2015');
