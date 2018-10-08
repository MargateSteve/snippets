SELECT g.club_id,
	g.club_name,
	Sum(CASE WHEN (f.ht_id = g.club_id) AND (f.outcome = "h") THEN 1 ELSE 0 END) AS HW,
	Sum(CASE WHEN (f.ht_id = g.club_id) AND (f.outcome = "d") THEN 1 ELSE 0 END) AS HD,
	Sum(CASE WHEN (f.ht_id = g.club_id) AND (f.outcome = "a") THEN 1 ELSE 0 END) AS HL,
	Sum(CASE WHEN (f.at_id = g.club_id) AND (f.outcome = "a") THEN 1 ELSE 0 END) AS AW,
	Sum(CASE WHEN (f.at_id = g.club_id) AND (f.outcome = "d") THEN 1 ELSE 0 END) AS AD,
	Sum(CASE WHEN (f.at_id = g.club_id) AND (f.outcome = "h") THEN 1 ELSE 0 END) AS AL,
	Sum(CASE WHEN (f.ht_id = g.club_id) THEN h90 ELSE 0 END) AS HGF,
	Sum(CASE WHEN (f.ht_id = g.club_id) THEN a90 ELSE 0 END) AS HGA,
	Sum(CASE WHEN (f.at_id = g.club_id) THEN a90 ELSE 0 END) AS AGF,
	Sum(CASE WHEN (f.at_id = g.club_id) THEN h90 ELSE 0 END) AS AGA
FROM fixture f
INNER JOIN (
	SELECT DISTINCT b.`club_id`,
		b.`club_name`,
		SUBSTRING_INDEX(GROUP_CONCAT(a.`fixture_id` ORDER BY a.`ko` DESC), ',', 6) AS 'game_ids'
	FROM fixture a
	INNER JOIN clubs b ON a.`at_id` = b.`club_id` OR a.`ht_id` = b.`club_id`
	WHERE a.season = "2017-18" AND a.comp = 1 AND a.playoff = 0 AND a.`status` = "ft"
	GROUP BY b.club_id
	) g ON FIND_IN_SET(f.`fixture_id`, g.game_ids) >= 1
GROUP BY g.club_id;
