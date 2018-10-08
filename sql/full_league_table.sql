SELECT 
Tid,
Tm,
W+D+L Pl,
W,
D,
L,
GF,
GA,
(W*3)+D Pts,
(HW*3)+HD HPts,
(AW*3)+AD APts,
GF-GA GD,
HGF-HGA HGD,
AGF-AGA AGD,
HW+HD+HL HPl,
HW,
HD,
HL,
HGF,
HGA,
HGF-HGA HGD,
(HW*3)+HD HPts,
AW+AD+AL APl,
AW,
AD,
AL,
AGF,
AGA,
AGF-AGA AGD,
(AW*3)+AD APts

FROM (
	SELECT  
	Tm,
	Tid,
	HW + AW W,
	HW,
	AW,
	HD + AD D,
	HD,
	AD,
	HL + AL L,
	HL,
	AL,
	HGF + AGF GF,
	HGF,
	AGF,
	HGA + AGA GA,
	HGA,
	AGA
	FROM (
		SELECT
		c.club_name AS Tm,
		c.club_id AS Tid,
		SUM(CASE WHEN (f.ht_id = c.club_id) AND (f.outcome = 'h') THEN 1 ELSE 0 END) AS HW,
		SUM(CASE WHEN (f.at_id = c.club_id) AND (f.outcome = 'a') THEN 1 ELSE 0 END) AS AW,
		SUM(CASE WHEN (f.ht_id = c.club_id) AND (f.outcome = 'd') THEN 1 ELSE 0 END) AS HD,
		SUM(CASE WHEN (f.at_id = c.club_id) AND (f.outcome = 'd') THEN 1 ELSE 0 END) AS AD,
		SUM(CASE WHEN (f.ht_id = c.club_id) AND (f.outcome = 'a') THEN 1 ELSE 0 END) AS HL,
		SUM(CASE WHEN (f.at_id = c.club_id) AND (f.outcome = 'h') THEN 1 ELSE 0 END) AS AL,
		SUM(CASE WHEN (f.ht_id = c.club_id) THEN h90 ELSE 0 END) AS HGF,
		SUM(CASE WHEN (f.ht_id = c.club_id) THEN a90 ELSE 0 END) AS HGA,
		SUM(CASE WHEN (f.at_id = c.club_id) THEN a90 ELSE 0 END) AS AGF,
		SUM(CASE WHEN (f.at_id = c.club_id) THEN h90 ELSE 0 END) AS AGA
	FROM clubs c
	LEFT JOIN fixture f ON c.club_id IN (f.ht_id,f.at_id)
	WHERE f.season = '2017-18'
	AND f.comp = 11
	AND f.playoff IS NULL
	AND f.`status` = 'FT'
	GROUP BY c.club_id
	) core
) points
ORDER BY pts DESC, gd DESC
