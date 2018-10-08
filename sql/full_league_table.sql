SELECT tid,
	tm,
	w + d + l Pl,
	w,
	d,
	l,
	gf,
	ga,
	gf - ga GD,
	CASE WHEN deds IS NOT NULL THEN (w * 3) + d + deds ELSE (w * 3) + d END AS pts,
	CASE WHEN deds IS NOT NULL THEN (w * 3) + d + deds + (to_play * 3) ELSE (w * 3) + d + (to_play * 3) END AS poss,
	deds,
	hw + hd + hl HPl,
	hw,
	hd,
	hl,
	hgf,
	hga,
	hgf - hga HGD,
	(hw * 3) + hd HPts,
	aw + ad + al APl,
	aw,
	ad,
	al,
	agf,
	aga,
	agf - aga AGD
FROM (
	SELECT tm,
		tid,
		hw + aw W,
		hd + ad D,
		hl + al L,
		hw,
		hd,
		hl,
		aw,
		ad,
		al,
		hgf + agf GF,
		hga + aga GA,
		hgf,
		hga,
		agf,
		aga,
		(
			SELECT SUM(points)
			FROM points_deductions
			WHERE club = tid AND season = "2018-19" AND competition = 1
			) deds,
		(
			SELECT COUNT(fixture_id)
			FROM fixture
			WHERE tid IN (ht_id, at_id) AND season = "2018-19" AND comp = 1 AND `status` = "fx"
			) to_play
	FROM (
		SELECT c.club_name AS Tm,
			c.club_id AS Tid,
			SUM(CASE WHEN (f.ht_id = c.club_id) AND (f.outcome = "h") THEN 1 ELSE 0 END) AS HW,
			SUM(CASE WHEN (f.ht_id = c.club_id) AND (f.outcome = "d") THEN 1 ELSE 0 END) AS HD,
			SUM(CASE WHEN (f.ht_id = c.club_id) AND (f.outcome = "a") THEN 1 ELSE 0 END) AS HL,
			SUM(CASE WHEN (f.at_id = c.club_id) AND (f.outcome = "a") THEN 1 ELSE 0 END) AS AW,
			SUM(CASE WHEN (f.at_id = c.club_id) AND (f.outcome = "d") THEN 1 ELSE 0 END) AS AD,
			SUM(CASE WHEN (f.at_id = c.club_id) AND (f.outcome = "h") THEN 1 ELSE 0 END) AS AL,
			SUM(CASE WHEN (f.ht_id = c.club_id) THEN h90 ELSE 0 END) AS HGF,
			SUM(CASE WHEN (f.ht_id = c.club_id) THEN a90 ELSE 0 END) AS HGA,
			SUM(CASE WHEN (f.at_id = c.club_id) THEN a90 ELSE 0 END) AS AGF,
			SUM(CASE WHEN (f.at_id = c.club_id) THEN h90 ELSE 0 END) AS AGA
		FROM clubs c
		LEFT JOIN fixture f ON c.club_id IN (f.ht_id, f.at_id)
		WHERE f.season = "2018-19" AND f.comp = 1 AND f.playoff = 0 AND f.`status` = "ft"
		GROUP BY c.club_id
		) core
	) points
ORDER BY pts DESC,
	gd DESC,
	tm ASC
