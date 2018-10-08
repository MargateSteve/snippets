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

SELECT 
	Tid,
	Tm,
	TG,
	FT,
	FX,
	TBA,
	(FT/TG) * 100 PLPC,
	(FX/TG) * 100 NPLPC,
	(TBA/TG) * 100 TBALPC,
	
	HTG,
	HFT,
	HFX,
	HTBA,
	(HFT/HTG) * 100 HPLPC,
	(HFX/HTG) * 100 HNPLPC,
	(HTBA/HTG) * 100 HTBALPC,
	
	
	ATG,
	AFT,
	AFX,
	ATBA,
	(AFT/ATG) * 100 APLPC,
	(AFX/ATG) * 100 ANPLPC,
	(ATBA/ATG) * 100 ATBALPC
	
	FROM (
	SELECT  
		*, 
		HFT + HFX + AFT + AFX TG,
		HFT + AFT FT,
		HFX + AFX FX,
		HFT + HFX HTG,
		AFT + AFX ATG,
		HTBA + ATBA TBA
		FROM (
			SELECT
			c.club_name AS Tm,
			c.club_id AS Tid,
			SUM(CASE WHEN (f.ht_id = c.club_id) AND (f.`status` = 'FT') THEN 1 ELSE 0 END) as HFT, 
			SUM(CASE WHEN (f.ht_id = c.club_id) AND (f.`status` = 'FX') THEN 1 ELSE 0 END) as HFX, 
	
			SUM(CASE WHEN (f.at_id = c.club_id) AND (f.`status` = 'FT') THEN 1 ELSE 0 END) as AFT,
			SUM(CASE WHEN (f.at_id = c.club_id) AND (f.`status` = 'FX') THEN 1 ELSE 0 END) as AFX,
			
			SUM(CASE WHEN (f.ht_id = c.club_id) AND (f.status = 'FX') AND (f.ko) IS NULL THEN 1 ELSE 0 END) as HTBA,
			SUM(CASE WHEN (f.at_id = c.club_id) AND (f.status = 'FX') AND (f.ko) IS NULL THEN 1 ELSE 0 END) as ATBA
		FROM clubs c
		LEFT JOIN fixture f ON c.club_id IN (f.ht_id,f.at_id)
		WHERE f.season = '2017-18'
		AND f.comp = 11
		GROUP BY c.club_id
		) core
	) main
