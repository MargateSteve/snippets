/*
	TG - Total games 
	GP - Games played
	NP - Games not played
	PLPERC - Percenatage of total games that have been played
	NPLPERC - Percenatage of total games that have not been played
	TBA - Games to be arranged
	TBAPERC - Percenatage of total games that are to be arranged
	
	Add H or A to the front of anything for a home or away version
	
	Need to factor in postponed and abandoned as they will currently skew the figures
*/
SELECT c.club_id,
	c.club_name,
	/** Total games played / not played **/
	ROUND(sum(CASE WHEN c.club_id IN (f.ht_id, f.at_id) THEN 1 ELSE 0 END)) AS TG,
	sum(CASE WHEN f.ht_id = c.club_id THEN 1 ELSE 0 END) AS HTG,
	sum(CASE WHEN f.at_id = c.club_id THEN 1 ELSE 0 END) AS ATG,
	/** Total games played / not played **/
	sum(CASE WHEN (f.`status` = 'FT') THEN 1 ELSE 0 END) AS GP,
	sum(CASE WHEN (f.`status` = 'FX') THEN 1 ELSE 0 END) AS NP,
	ROUND(sum(CASE WHEN (f.ht_id = c.club_id) AND (f.`status` = 'FT') THEN 1 ELSE 0 END)) AS HGP,
	ROUND(sum(CASE WHEN (f.ht_id = c.club_id) AND (f.`status` = 'FX') THEN 1 ELSE 0 END)) AS HNP,
	ROUND(sum(CASE WHEN (f.at_id = c.club_id) AND (f.`status` = 'FT') THEN 1 ELSE 0 END)) AS AGP,
	ROUND(sum(CASE WHEN (f.at_id = c.club_id) AND (f.`status` = 'FX') THEN 1 ELSE 0 END)) AS ANP,
	/** % of games played / not played **/
	sum(CASE WHEN (c.club_id IN (f.ht_id, f.at_id)) AND (f.`status` = 'FT') THEN 1 ELSE 0 END) / sum(CASE WHEN (c.club_id IN (f.ht_id, f.at_id)) THEN 1 ELSE 0 END) * 100 AS PLPERC,
	sum(CASE WHEN (c.club_id IN (f.ht_id, f.at_id)) AND (f.`status` = 'FX') THEN 1 ELSE 0 END) / sum(CASE WHEN (c.club_id IN (f.ht_id, f.at_id)) THEN 1 ELSE 0 END) * 100 AS NPLPERC,
	sum(CASE WHEN f.ht_id = c.club_id AND (f.`status` = 'FT') THEN 1 ELSE 0 END) / sum(CASE WHEN (f.ht_id = c.club_id) THEN 1 ELSE 0 END) * 100 AS HPLPERC,
	sum(CASE WHEN f.ht_id = c.club_id AND (f.`status` = 'FX') THEN 1 ELSE 0 END) / sum(CASE WHEN (f.ht_id = c.club_id) THEN 1 ELSE 0 END) * 100 AS HNPLPERC,
	sum(CASE WHEN f.at_id = c.club_id AND (f.`status` = 'FT') THEN 1 ELSE 0 END) / sum(CASE WHEN (f.at_id = c.club_id) THEN 1 ELSE 0 END) * 100 AS APLPERC,
	sum(CASE WHEN f.at_id = c.club_id AND (f.`status` = 'FX') THEN 1 ELSE 0 END) / sum(CASE WHEN (f.at_id = c.club_id) THEN 1 ELSE 0 END) * 100 AS ANPLPERC,
	/** Total games to be arranged **/
	ROUND(sum((CASE WHEN (c.club_id IN (f.ht_id, f.at_id)) AND f.STATUS = 'FX' AND f.ko IS NULL THEN 1 ELSE 0 END)) / 2) AS TBA,
	ROUND(sum((CASE WHEN (f.ht_id = c.club_id) AND f.STATUS = 'FX' AND f.ko IS NULL THEN 1 ELSE 0 END)) / 2) AS HTBA,
	ROUND(sum((CASE WHEN (f.at_id = c.club_id) AND f.STATUS = 'FX' AND f.ko IS NULL THEN 1 ELSE 0 END)) / 2) AS ATBA,
	/** % of games to be arranged **/
	sum((CASE WHEN (c.club_id IN (f.ht_id, f.at_id)) AND f.STATUS = 'FX' THEN 1 ELSE 0 END)) / sum(CASE WHEN (c.club_id IN (f.ht_id, f.at_id)) AND f.STATUS = 'FX' THEN 1 ELSE 0 END) * 100 AS TBAPERC,
	sum((CASE WHEN (f.ht_id = c.club_id) AND f.STATUS = 'FX' THEN 1 ELSE 0 END)) / sum(CASE WHEN (f.ht_id = c.club_id) AND f.STATUS = 'FX' AND f.ko IS NULL THEN 1 ELSE 0 END) * 100 AS HTBAPERC,
	sum((CASE WHEN (f.at_id = c.club_id) AND f.STATUS = 'FX' THEN 1 ELSE 0 END)) / sum(CASE WHEN (f.at_id = c.club_id) AND f.STATUS = 'FX' AND f.ko IS NULL THEN 1 ELSE 0 END) * 100 AS ATBAPERC
FROM clubs c
LEFT JOIN fixture f ON c.club_id IN (ht_id, f.at_id)
WHERE f.season = '2017-18' AND f.comp = 11 AND f.playoff IS NULL
GROUP BY c.club_id
