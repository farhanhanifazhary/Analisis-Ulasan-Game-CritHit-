-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Nov 2025 pada 09.50
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crithit_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `article`
--

INSERT INTO `article` (`id`, `user_id`, `title`, `content`, `image_url`, `created_at`) VALUES
(1, 1, 'Babel', 'In the year 1094, on the outskirts of Kazdel, Ascalon is intercepted by Scareye, a Cyclops who leads Scar Market, the largest black market in Kazdel. Scareye taunts Ascalon that she has fallen for their diversion and revealed that Babel has been betrayed, with their leader, Theresa, killed by assassins sent from Theresis and the Military Commission of Kazdel. Scareye also declares his intent to kill Ascalon as well, in an attempt to fight against the fate he foresaw, where he will die at the hands of Ascalon. The two then engage in an intense fight before falling off the edge of the cliff.\r\n\r\nIn Londinium, a group of assassins stood down as their objective, Theresis, has announced the death of Theresa. With this, he is now the sole leader of Kazdel, and demands that the assassins swear fealty to him. Despite their reluctance, they agree to do so, knowing that their cause is lost. After they had sworn their new oath of alligence to Theresis, they were given the next task: to enter the Mausoleum of Kings and stage an ambush to kill all Steam Knights, the most elite force of Victoria. Knowing that this mission will certainly mean death to almost everyone involved, the assassins reluctantly complied, carrying their grief for Theresa and hatred for Theresis to their inevitable graves.\r\n\r\nMeanwhile on the Rhodes Island landship, an enraged Kal&amp;amp;amp;amp;#039;tsit leads Mon3tr into a rampage against assassins aboard. As one assassin bleeds out, he begs Kal&amp;amp;amp;amp;#039;tsit not to reveal their names and face to the public, as they are too ashamed of their actions. After reaching the room of Theresa&amp;amp;amp;amp;#039;s office, Kal&amp;amp;amp;amp;#039;tsit orders Mon3tr to tear down the locked door immediately, hoping for the slightest chance at saving her friend. Unfortunately, it is too late: Theresa lies in a pool of her own blood, with Amiya tightly held in her clutches. Dozens of hornless assassins are strewn dead across the floor. The only other survivor is Doctor, who lies unconscious right next to Theresa.\r\n\r\nKal&amp;amp;amp;amp;#039;tsit worst fear is confirmed. Theresa is dead, and the cause of Tower of Babel died with her.[1]\r\n\r\nVision and Purpose\r\n\r\nYou will always stand by my side, right?\r\nIt all began in the Terran year of 1030, during the reign of the twin Sarkaz kings, Theresa and Theresis. At the time, there was an ongoing war between Gaul and Leithanien, and other Terran nations were becoming increasingly more advanced while Kazdel was left far behind. The two Kings saw a need to reshuffle the War Council and form the Military Commission to strengthen their power. They discussed with the other members of the Royal Court: Nezzsalem of the Nachzehrer Court, Laqeramaline of the Banshee Court, and Duq&amp;amp;amp;amp;#039;arael of the Vampire Court. Theresa had also managed to get the Damazti Cluster&amp;amp;amp;amp;#039;s approval. In addition to the Commission, Theresa has also been working together with Kal&amp;amp;amp;amp;#039;tsit to form Babel, an institution where Sarkaz and other races could become equals and work together as one.[2]\r\n\r\nIn the year 1068, a Leithanien Kurfürst collaborated with several Siracusan famiglie in order to destroy Kazdel. The Nachzehrers and Banshees held off their advance while the nomadic city charged into the middle of a Catastrophe to evade them. While leading the civilians to safety, the two Kings met a young Ascalon and decide to take her in. It was also around this time that Manfred joined a Military Commission institution and studied under Theresis. The two would later become rivals who were constantly at each others&amp;amp;amp;amp;#039; throats.[3]\r\n\r\nOver the years, Babel tried to foster a good reputation with the local Kazdelian Sarkaz by offering food, medicine, and education. However, since its inception, there had already been brewing hostility and resentment towards its members, partly fueled by hatred towards non-Sarkaz, signaled by isolated incidents of conflict. People were afraid to send their children to study under Babel, though some participated in secret tutoring sessions. Tensions spiked in 1086 when a Babel teacher accidentally killed someone from the Military Commission. To avoid further confrontation, Babel, joined by Theresa, withdrew from the city. Over time, more and more began to side with Regent Theresis&amp;amp;amp;amp;#039; ideals, believing in a new future at Londinium. Seeing that even the Royal Court and the Confessarius has sided with him, Theresa solemnly accepts her people&amp;amp;amp;amp;#039;s decision and leaves Kazdel. Several people, like Julie, choose to follow her. Ascalon decides to leave with Theresa, while Manfred remains by Theresis&amp;amp;amp;amp;#039; side.', 'https://arknights.wiki.gg/images/EN_Babel_banner.png?abba74', '2025-11-18 12:28:38'),
(5, 1, 'Masses Travel', 'The year is 1101. It is just an ordinary day. Suddenly, the wings and halos of Sankta outside of Laterano blacken and shatter. It affects the pious nuns of Bolívar, Father Agenir in Siracusa, Woodrow and Cliff in Columbia, and even Ezell in Kazdel. All of this points to a sign: something terrible has happened to the Holy City.[1]\r\n\r\nYet within Laterano, nothing seems to have changed. In fact, everyone seems to be more vigorous than before. Laterano is hosting the Second Summit of Nations to promote a new Lateran order in face of an impending prophesied calamity on Terra. Even Kazdel and Ægir have sent delegations to the Holy City, willing to band together to face the prophesied calamity that will befall Terra. The future of Laterano has never been brighter.[2]\r\n\r\nWith everyone in Penguin Logistics out doing their own thing, Exusiai decides that now is the perfect time to visit her hometown in Laterano. As she is filling out her application for leave, she is visited by a couple of Rhodes Island Operators, who present her with classified files from the landship. Exusiai learns that something strange has happened in Laterano, and decides to see the situation with her own two eyes.[3]\r\n\r\nIn the past several years since the first Summit of Nations, Laterano has been undergoing massive reformations. The Curia has been enacting extensive arms control policies, cracking down on unsanctioned gunshots and seizing explosive materials in order to keep things safe and secure. In efforts to standardize weapons productions, they have also been closing down all firearms workshops across the country, which place more importance on customization rather than efficiency. Furthermore, plans are being drafted to allow non-Sankta to wield their own patron firearms. To some, this may seem like progress; to Exusiai, it is erasing everything that made Laterano unique, and it seems that nobody thinks that these regulations are strange.[4]', 'https://arknights.wiki.gg/images/EN_The_Masses%27_Travels_banner.png?58b930', '2025-11-14 02:37:52'),
(6, 1, 'Under Tides', 'During her usual day in Rhodes Island, the Aegir bounty huntress Skadi heard a song by her fellow kind. Feeling something\'s off with it, Skadi hurries to check up on Specter, only to see her supposedly dead superior in the Abyssal Hunters, Gladiia, taking her away. Gladiia tells Skadi that she have her own reasons for taking Specter, and if she wishes to know why, she should come to Sal Viento, an Iberian nomadic city.\r\n\r\nMeanwhile, terrifying aquatic monsters were spotted in the premises of the said city. An unlucky Kazimierzian bounty hunter spotted the monsters and almost became victim to their attacks, only to be saved by the Inquisitors of Iberia. Unfortunately, the Inquisitors executed him to prevent any information leaks, while a mysterious bishop and a humanoid monster discusses about an agreement between them.', 'https://arknights.wiki.gg/images/EN_Under_Tides_banner.png?9b43fe', '2025-11-14 02:43:34'),
(7, 1, 'Goddess Fall', 'Johan asks Cecil for an update on the Queen, which the scientist reports is currently lingering in the stratosphere 48 kilometers above the surface. Johan finds this odd, since the Queen has remained stationary for three days now, after making what was seemingly a meteoric descent from orbit. Noah sneers that the Queen is afraid of directly confronting Eden, and so has chosen to remain high above the enclave, visible but out of reach. Johan is much less certain of that idea.\r\n\r\nHarran asks Isabel to fly up and perform reconnaissance, but the latter warns that even her flight systems can\'t take her that high. Nevertheless, she considers the idea of at least getting visual confirmation of the Queen, to which Noah chimes that such wording often leads to Isabel doing exactly what she seemed unsure of. Johan authorizes Isabel to fly out and reconnoiter the Queen, the flyer trading banter with Noah over the latter catching the former should the Queen knock her down.\r\n\r\nWith that, Johan turns to Cecil for an update on Eden\'s Spear, and whether it can fire again. Good news, the cannon can fire soon thanks to devoting all of Eden\'s energy sources and extra generators to charging it. Bad news, they can only expect 50% output at best. There is the option of devoting absolutely everything to achieve an output close to 100%...but the draws and backlash would destroy all of Eden in the process. Johan doesn\'t mind the risk, urging Cecil to do whatever it takes to get a full-power shot. Harran muses on how Johan is willing to even sacrifice Eden for this, to which the Inherit commander declares his resolve. With the Queen stationary, this is the best chance they have to take her down for good.\r\n\r\nWhile Cecil agrees to do as Johan is suggesting, Noah wonders why the Queen is just sitting in the air above, not moving, attacking, or...anything, really. Cecil posits that the Queen has either lost power to descend, or has suffered some systems malfunction. They\'ve no idea what the cause could be at this juncture. Isabel jokingly proposes asking the Queen when she flies up there, much to Harran\'s mirth. A feeling shared by the rest of Inherit, now that their final adversary is right at their doorstep. Noah notices Johan\'s face is screwed into consternation, and she asks if he\'s swallowed a bug. Denying such allegations and refusing to consider how Noah would know the face a bug-eater would make, Johan airs his concerns over the Queen\'s lack of activity. Cecil snarks that if anyone is going to figure it out, it\'d be him, who is the field operations command of Eden.\r\n\r\nOutside of Eden, Snow White bluntly refuses, much to Nayuta\'s expectations. Scarlet is of a similar mind; Pioneer gathered here from their own wanderings, on Nayuta\'s summons no less, and the first thing the monk says is suggesting they ally with Eden, knowing full well that the two sides have irreconcilable differences. Nayuta argues that they both want the same thing, a point Rapunzel concedes. Yet even then, neither will budge on their methodology. Though Nayuta quips that Eden hasn\'t tried something drastic like sacrificing the Ark and everyone in it to destroy the Queen, Rapunzel points out that no one can say Eden wouldn\'t attempt that if they deemed it necessary to defeat the Queen.\r\n\r\nIdeals aside, Snow White refuses to ally with Eden on grounds that Dorothy is there, and none in Pioneer can predict or trust the schemes of their erstwhile leader. That\'s when Nayuta reveals that Dorothy has left Eden...to reside in the Ark instead, after taking part in sealing the breach that Raptures used to enter the colony.[1] Hearing that their ex-leader is now in the very colony she\'s on record for despising, Pioneer sets themselves to beeline for the Ark to meet with Dorothy and suss out her motives. Their hope is that \"he\" will have a way of getting Dorothy out of the Ark, but as Nayuta explains, \"Great Hero\" is currently out of the Ark aboard the \"White Giant,\" and thus cannot assist Pioneer.[2] Despite that, none of Pioneer is willing to hear Nayuta out...not until she reveals the Queen will imminently descend upon this very place.\r\n\r\nAll of Pioneer stares in frozen silence until Snow White asks for clarification. Nayuta explains that Eden is preparing to intercept the Queen as she descends, but deems it unlikely for the battle to end so quickly. She beseeches the Pilgrims to put aside their differences to work with Eden, but her plea is cut short as she turns her gaze to the sky. Innocuous at first, until she spies it. A red glow, dyeing the very clouds that line it. The monk curses as she witnesses the light growing in intensity', 'https://www.dearplayers.com/_next/image?url=https%3A%2F%2Fassets.dearplayers.com%2Fgplay-data%2Fevents%2Fnew-event-goddess-fall-6754421225-1280x720sr.jpg&amp;amp;w=1200&amp;amp;q=70', '2025-11-14 03:47:19'),
(8, 1, 'Overzone', 'A video feed plays, a recording set by its creator Liliweiss to play when her life signals cease. Loath as she is to use the term, she calls this video her will. Liliweiss reminisces on the moments she spent with her comrades, and fighting alongside her commander. She tells the viewers that she empathizes with their feelings, of wanting to give up after all the tragedy they\'ve been through, as she\'s felt the same more than once. Yet, both she and the viewers have gone on to protect humanity, and they should take pride in that. Liliweiss ends the video with a farewell to her friends, asking them to take care and survive.', 'https://static.wikia.nocookie.net/nikke-goddess-of-victory-international/images/1/19/Over_Zone_splash_2.jpg', '2025-11-14 03:41:12'),
(9, 1, 'Lion Heart', 'A child gawks at Leona as he passes by, mistaking her lion Timi for a stuffed animal. His parents are quick to double-take, realizing that Leona is carrying a real lion with her and becoming concerned that the animal rescue staff are just letting animals roam free in the Ark. The child eagerly runs up to Leona asking if he can pet Timi. She\'s happy to oblige, but the child\'s mother interrupts.\r\n\r\nThe mother goes on a long-winded tirade berating Leona for letting a \"wild animal\" into the Ark. She\'s quick to cease her rant as Timi\'s growl intimidates her, and the family beats tracks. Leona silently watches them go', 'https://static.wikia.nocookie.net/nikke-goddess-of-victory-international/images/9/93/Lion_Heart_splash_2.jpg', '2025-11-14 03:46:31'),
(10, 1, 'Mon3tr', 'Mon3tr is a mysterious creature related to Kal\'tsit and is always seen together with her. After Kal\'tsit severed their symbiotic link, her \"deletion\" at the hands of Priestess causes Mon3tr to retreat into her crystal form and slowly \"grow\" a human body.\r\n\r\nAs Mon3tr is neither a manifestation of Originium Arts nor a true living creature, her existence is a mystery to most, unaware of her origins as a Predecessor creation. She possesses a form of symbiotic relationship with Kal\'tsit that allows them to communicate, as well as sense the status of the other even when separated. When inactive or for convenience, Mon3tr can assume the form of a floating octahedral crystal with black and green hues.[note 3] In her monster form she appears as a crystalline, green, lizard-like monster and possesses a highly durable, metallic-like skin, making her immune to most conventional weaponry. On top of sheer strength that allows her to easily cut down even the most hardy of enemies with razor-sharp scythes, she is able to charge up energy in her mouth and launch it as an explosive laser.[4]', 'https://arknights.wiki.gg/images/Mon3tr_Elite_2.png', '2025-11-18 13:00:47'),
(11, 1, 'Theresa', 'Theresa was the previous \"King of Sarkaz\" of Kazdel, known as a gentle, down-to-earth ruler who wished to end the never-ending cycle of nation\'s fragmentation and bring peace to the Sarkaz people. Once a humble tailoress and student of Nezzsalem the Nachzehrer King, her works eventually drew the attention of the Sarkaz Royal Court, and even got the chance to meet the King of Sarkaz at the time, Yliš.[1][2] She bore witness to the destruction brought by the crusade alliance of Victoria, Gaul, and Leithanien during their invasion in the Terran year 898. Thereby, she and her brother Theresis were the first of the \"Six Heroes\" who reorganized the troops and step forward to try to change the tides of war. After Yliš was killed in action, the \"Black Crown\" chose her and Theresis to become the new rulers of the Sarkaz, but the later willingly decided to resign and give the honors to his sister. Theresa became the first crossbreed King of Sarkaz since Qui\'lon.[3][4][5]\r\n\r\nIn the following years since Kazdel\'s bittersweet victory, Theresa had been cooperating with Theresis and the Sarkaz Royal Court to rebuild the nation, including the capital\'s reconstruction into a modern Nomadic city, and the establishment of the Kazdelian War Council, the Military Commission\'s predecessor.[6] It was in this period when she established Babel, an institution that sought to improve the Sarkaz\' lifehood and treatment from foreigners.; however, over time, Theresis began to lose hope in Babel\'s vision of reuniting Kazdel without violence, coming to believe that the other Terran nations would only respect them through force.[7][8] Years later, in 1086, tensions between the Military Commission and Babel began to escalate further after an incident on which a Babel teacher accidentally injured a pro-Commission teacher, which culminated with the Military Commission launching an artillery shell to strike one of Babel\'s clinics. The incident not only ignited another round of civil conflict, but it also forced her into exile. [9][10]\r\n\r\nDuring her exile, Theresa used Babel to unite those who remained loyal to her, with Closure, Kal\'tsit, and the Doctor among her loyalists. She also welcomed those of any race to join the organization, leading to discontent among some Sarkaz nationalists, setting the Rhodes Island ship as he main base of operations and palace. However, with her powers, which allowed her to tap into and manipulate the emotions of others, she was able to pacify anyone, regardless of their loyalty or hostility towards her. At this time, she acted as the caretaker for the organization\'s members, notably helping to raise the young Amiya. Theresa hoped to guide Amiya\'s growth and have her become a silver lining to Terra in the future. [9]\r\n\r\nThe Babel-Military Commission war reached its climax in 1094, when Theresis launched a siege against Babel during the autumn and conducted a \"decapitation operation.\" It turns out that the Doctor had secretly negotiated with Theresis to fracture Babel from the inside in exchange of providing him with the required technology to control Originium, hence why they disabled the Rhodes Island\'s shipwide defense systems and sent out all field Operators to \"retake Kazdel\".[11] With the landship vulnerable to the Military Commission\'s attacks, a squad of Deathveil Assassins was sent to hunt down Theresa, thus breaking Babel apart. Theresa fought the assassins until the last breath. Before finally falling to her injuries, Theresa had bestowed the Civilight Eterna to little Amiya, and erased the Doctor\'s memories so they could \"start anew,\" thus being the cause of their amnesia. Because of the Doctor\'s involvement, Theresa\'s death is often considered their betrayal by many of her supporters, especially Kal\'tsit.[3][12][13]', 'https://i.namu.wiki/i/67mbN7zbvgnJlOMdYt2fFT2cqFw6qFRUi_n2ivq-JnQVlXu1NvOr0NHEmVFcG3XogF2a4LCYLCevOhkpf0qYlg.webp', '2025-11-30 08:03:19'),
(12, 1, 'Seven Dwarves', 'In the lore of Goddess of Victory: Nikke, Snow White is a Pilgrim—a Nikke who wanders the Surface alone, fighting the Raptures without the support of the Ark. To survive in such a hostile environment, she cannot rely on a single weapon.\r\n\r\nHer solution is the Seven Dwarves. It is not merely a gun, but a massive, modular weapon case that houses seven different tactical systems. This allows Snow White to adapt to any combat situation, effectively making her a \"one-woman army.\"\r\n\r\n1. The Concept and Design\r\nThe weapon is named after the fairytale Snow White and the Seven Dwarfs. Just as the dwarves in the story were miners and workers with different tools, Snow White\'s weapon system provides her with a different \"tool\" for every obstacle she faces on the Surface.\r\n\r\nThe system is designed for extreme versatility. Since Snow White rarely receives maintenance or supplies from the Ark, the Seven Dwarves allows her to switch roles instantly—from crowd control to heavy demolition—without needing to change her loadout at a base.\r\n\r\n2. The Known Forms (The Dwarves)\r\nWhile not all seven forms are frequently shown in gameplay, several have been revealed through her skills, cutscenes, and her alternate version (Snow White: Innocent Days).\r\n\r\nSeven Dwarves: I (The Anti-Ship Rifle) This is the most iconic form, used during Snow White\'s Burst Skill. It transforms the weapon case into a massive railgun/anti-ship rifle capable of piercing through the thickest Rapture armor. In the lore, this weapon is powerful enough to severely damage Tyrant-class Raptures in a single shot. It represents pure, concentrated destruction.\r\n\r\nSeven Dwarves: II (The Missile Launcher) Often referenced in lore and seen in parts of the story, this mode functions as a heavy ordnance launcher. It is used for crowd control or dealing with clusters of enemies, distinct from the precision of Mode I.\r\n\r\nSeven Dwarves: III (The Grappling Hook/Mobility Tool) Seen in story cinematics (particularly during the OverZone event or main story chapters), this form allows Snow White to maneuver vertically, scale buildings, or pull enemies. It highlights that the Seven Dwarves system includes utility, not just firepower.\r\n\r\nSeven Dwarves: V & VI (The Twin Assault Rifles) These are the primary weapons used by the younger version of the character, Snow White: Innocent Days. Unlike the adult Snow White who favors heavy single shots, the younger version dual-wields these assault rifles for rapid suppression fire. It shows that the system was originally designed for high-speed engagement before Snow White adapted her fighting style to heavy artillery.\r\n\r\n3. Lore Significance\r\nThe Seven Dwarves system is a tragic reminder of Snow White\'s history.\r\n\r\nA Relic of the Goddess Squad: The weapon utilizes \"Legacy Technology\" from the era of the Goddess Squad, far superior to most modern Nikke weaponry manufactured by the Big Three (Elysion, Missilis, Tetra).\r\n\r\nThe Weight of Survival: Carrying the massive weapon case on her back symbolizes the burden Snow White carries. She has undergone a \"Mind Switch\" (a psychological break common in Nikkes), causing her to lose many memories and emotions. The weapon is one of the few constants in her life—it is her protector, her tool, and her only companion in the desolate Surface.\r\n\r\nConclusion\r\nThe Seven Dwarves is one of the most mechanically complex weapons in the Nikke universe. It transforms Snow White from a simple soldier into a versatile survivalist. Whether she needs to grapple up a ruin, mow down a horde of minions (Mode V/VI), or obliterate a Lord-class Rapture with a railgun shot (Mode I), the weapon ensures that the \"Princess\" of the Surface never needs a Prince to save her—she can save herself.', 'https://i0.wp.com/news.qoo-app.com/en/wp-content/uploads/sites/3/2022/10/nikke_snow_white_trailer_00Layer-4.jpg?resize=900%2C506&amp;ssl=1', '2025-11-30 07:52:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `article_games`
--

CREATE TABLE `article_games` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `article_games`
--

INSERT INTO `article_games` (`id`, `article_id`, `game_id`) VALUES
(18, 1, 1),
(5, 5, 1),
(6, 6, 1),
(17, 7, 4),
(15, 8, 4),
(16, 9, 4),
(22, 10, 1),
(32, 11, 1),
(31, 12, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `game_id`) VALUES
(12, 2, 1),
(10, 2, 4),
(8, 6, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `games`
--

INSERT INTO `games` (`id`, `title`, `description`, `release_date`, `image_url`, `created_at`) VALUES
(1, 'Arknights', 'Arknights is a free-to-play tactical RPG/tower defense mobile game developed by Hypergryph. It was released in China on 1 May 2019, in other countries on 16 January 2020, and in Taiwan on 29 June 2020. Arknights is available on Android and iOS platforms and features gacha game mechanics.', '2020-01-16', 'https://arknights.wiki.gg/images/Amiya_icon.png?571cdd', '2025-11-05 12:48:53'),
(4, 'Goddess of Victory: NIKKE', 'Goddess of Victory: Nikke is a third-person shooter action role-playing video game developed by Shift Up and published by Level Infinite. Development of Nikke began as early as 2017, and it was released for Android and iOS in 2022, and Windows in 2023.', '2022-11-04', 'https://nikke-en.com/pc/ossweb-img/footer_spec_icon.png', '2025-11-14 03:29:48'),
(5, 'Zenless Zone Zero', 'https://img.utdstc.com/icon/aa7/3cf/aa73cff70dbc44cc34ca16d5ec0d19d2d3465a52ef5ff019f4b602e7d316e534:200', '2025-06-06', 'https://img.utdstc.com/icon/aa7/3cf/aa73cff70dbc44cc34ca16d5ec0d19d2d3465a52ef5ff019f4b602e7d316e534:200', '2025-11-29 01:23:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `game_genres`
--

CREATE TABLE `game_genres` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `game_genres`
--

INSERT INTO `game_genres` (`id`, `game_id`, `genre_id`) VALUES
(7, 1, 1),
(10, 4, 3),
(11, 5, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `game_publishers`
--

CREATE TABLE `game_publishers` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `publisher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `game_publishers`
--

INSERT INTO `game_publishers` (`id`, `game_id`, `publisher_id`) VALUES
(6, 1, 1),
(8, 1, 3),
(7, 1, 4),
(11, 4, 5),
(17, 5, 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `genres`
--

INSERT INTO `genres` (`id`, `name`) VALUES
(4, 'Hack and Slash'),
(3, 'RPG-shooter'),
(1, 'Tower Defense');

-- --------------------------------------------------------

--
-- Struktur dari tabel `publisher`
--

CREATE TABLE `publisher` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `publisher`
--

INSERT INTO `publisher` (`id`, `name`, `country`) VALUES
(1, 'Hypergryph', 'Cina'),
(3, 'Yostar', 'Tiongkok'),
(4, 'X.D. Network Inc.', 'Tiongkok'),
(5, 'Shift Up', 'Korea Selatan'),
(6, 'miHoYo', 'Cina');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `comment_text` text NOT NULL,
  `sentiment` enum('positive','negative','neutral','gagal') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `reviews`
--

INSERT INTO `reviews` (`id`, `game_id`, `user_id`, `rating`, `comment_text`, `sentiment`, `created_at`) VALUES
(14, 1, 2, 5, 'Game ini sangat bagus. Aku suka banget storynya. Aku tidak sabar menunggu update selanjutnya.', 'positive', '2025-11-14 00:03:24'),
(20, 1, 4, 1, 'Jelek. Lemot', 'negative', '2025-11-14 00:41:13'),
(22, 1, 5, 3, 'biasa aja', 'positive', '2025-11-14 00:43:37'),
(29, 4, 6, 1, 'Gamenya jelek. Lemot dan sering lag', 'negative', '2025-11-29 00:42:35'),
(48, 5, 2, 5, 'Aku suka', 'positive', '2025-11-30 01:31:35'),
(49, 4, 2, 4, 'Gamenya seru, cuman gameplay kurang. Tapi ceritanya masterpiece', 'positive', '2025-11-30 06:46:27'),
(50, 1, 6, 5, 'Story-nya bagus', 'positive', '2025-11-30 08:08:47'),
(51, 5, 6, 1, 'Tidak suka', 'negative', '2025-11-30 08:09:18'),
(53, 4, 4, 2, 'Gameplay kurang menarik. Jadi malas main', 'negative', '2025-11-30 08:47:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Aku', 'aku@gmail.com', '$2y$10$/TSa.fpV397nCqYMP.s5Lu1/17KNunzmPZYNnqIEAsUL.Q7rhcdVu', 'admin', '2025-11-06 12:59:32'),
(2, 'Hanif', 'hanif@gmail.com', '$2y$10$hHEzMFue9Ysxds7Qlr8nGO8gLvQnAstm5uri9SWixe9ckJSOtNW6i', 'user', '2025-11-10 22:43:54'),
(4, 'Talulah Artorious', 'talulah@gmail.com', '$2a$12$fc/M8p1yc4QTdbGt5xZ5Ruxk6kqOiIxEtByNpwgmveFSVRY7CgdGS', 'user', '2025-11-14 00:05:15'),
(5, 'Cecil', 'cecil@gmail.com', '$2y$10$XOyvejNiuiEvaRStx31nZu2aHKmCyoQq40SY9A1BH20NtPPrbPNMW', 'user', '2025-11-14 00:41:53'),
(6, 'Skadi', 'skadi@gmail.com', '$2y$10$93Y.lqRGRqh9tOxFs8Z6r.BDkccyq9YSYCRRbnBnmSWvD86JyBS/S', 'user', '2025-11-29 00:41:37'),
(8, 'Amiyi', 'amiyi@gmail.com', '$2y$10$ULpaBjuCUibJsWqL..05v.bnBh5WEVkgeLy8sLzuKVSNPcZxzopIm', 'user', '2025-11-30 08:48:23');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `article_games`
--
ALTER TABLE `article_games`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UC_article_game` (`article_id`,`game_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indeks untuk tabel `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UC_favorite` (`user_id`,`game_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indeks untuk tabel `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `game_genres`
--
ALTER TABLE `game_genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UC_game_genre` (`game_id`,`genre_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indeks untuk tabel `game_publishers`
--
ALTER TABLE `game_publishers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UC_game_publisher` (`game_id`,`publisher_id`),
  ADD KEY `publisher_id` (`publisher_id`);

--
-- Indeks untuk tabel `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `publisher`
--
ALTER TABLE `publisher`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `article_games`
--
ALTER TABLE `article_games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `game_genres`
--
ALTER TABLE `game_genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `game_publishers`
--
ALTER TABLE `game_publishers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `publisher`
--
ALTER TABLE `publisher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `article_games`
--
ALTER TABLE `article_games`
  ADD CONSTRAINT `ag_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ag_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`);

--
-- Ketidakleluasaan untuk tabel `game_genres`
--
ALTER TABLE `game_genres`
  ADD CONSTRAINT `game_genres_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `game_genres_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`);

--
-- Ketidakleluasaan untuk tabel `game_publishers`
--
ALTER TABLE `game_publishers`
  ADD CONSTRAINT `gp_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `gp_ibfk_2` FOREIGN KEY (`publisher_id`) REFERENCES `publisher` (`id`);

--
-- Ketidakleluasaan untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
