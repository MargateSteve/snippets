SELECT
	*,
	(FX/VG)*100 FXPERC,
	(FT/VG)*100 FTPERC,
	(HW/FT)*100 HWPERC,
	(AW/FT)*100 AWPERC,
	(D/FT)*100 DPERC,
	HG + AG TG,
	HG/(HG + AG)*100 HGPERC,
	AG/(HG + AG)*100 AGPERC,
	(HG + AG)/ FT GPG,
	HG / FT HGPG,
	AG / FT AGPG,
	TATT / FT AVATT
	FROM
		(SELECT 
			COUNT(`fixture_id`) T,
			SUM(CASE WHEN `status` = 'FX' OR `status` = 'FT' THEN 1 ELSE 0 END) as VG,
			SUM(CASE WHEN `status` = 'FX' THEN 1 ELSE 0 END) as FX,
			SUM(CASE WHEN `status` = 'FT' THEN 1 ELSE 0 END) as FT,
			SUM(CASE WHEN `status` = 'AB' THEN 1 ELSE 0 END) as AB,
			SUM(CASE WHEN `status` = 'PP' THEN 1 ELSE 0 END) as PP,
			SUM(CASE WHEN `outcome` = 'h' THEN 1 ELSE 0 END) as HW,
			SUM(CASE WHEN `outcome` = 'd' THEN 1 ELSE 0 END) as D,
			SUM(CASE WHEN `outcome` = 'a' THEN 1 ELSE 0 END) as AW,
			SUM(CASE WHEN `extra_time` = '1' THEN `h120` ELSE `h90` END) as HG,
			SUM(CASE WHEN `extra_time` = '1' THEN `a120` ELSE `a90` END) as AG,
			SUM(CASE WHEN `att` IS NOT NULL THEN `att` ELSE 0 END) as TATT
		FROM `fixture` 
		WHERE `season` = '2017-18'
		AND `comp` = 11) AS CORE
