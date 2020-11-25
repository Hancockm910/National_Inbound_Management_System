/*
*
*		NIMS
*
*/


/* Ocean file */

ALTER TABLE master_file ADD Stevedore VARCHAR(40);
ALTER TABLE master_file ADD Wharf_Availablity DATE;

DROP TABLE NIMS.master_file;
CREATE TABLE master_file(
  UniqueNo                   VARCHAR(40),
  Shp_Id              	  	 VARCHAR(40) NOT NULL,
  Shp_CarName          		   VARCHAR(40),
  ShpRou_RecPldArrDate 		   DATE,
  LuTr_LuId            	   	 VARCHAR(40),
  ShpCar_BLid         	  	 VARCHAR(40),
  LuTr_LutCode         	  	 VARCHAR(40),
  Csm_ConsigneeCode    	  	 VARCHAR(40),
  Csm_ConsignorCode    	  	 VARCHAR(40),
  ShpRou_StartCode     	  	 VARCHAR(40),
  ShpRou_StartType     	  	 VARCHAR(40),
  ShpRou_EndCode       	  	 VARCHAR(40),
  ShpRou_EndType        		 VARCHAR(40),
  LuTr_SealNo		   		       VARCHAR(40),
  Shp_DateStart		   		     DATE,
  Shipment_Type		   	     	 VARCHAR(40),
  Land_Carrier		   	    	 VARCHAR(40),
  Ccs_SumVolGroAct	   	  	 FLOAT,
  Ccs_SumWeiGkg	   	  	     FLOAT,
  PRIO                       VARCHAR(40),
  Wharf_Availability         DATE,
  IN_YARD_DATE               DATE,
  Stevedore                  VARCHAR(40),
  PRIMARY KEY(UniqueNo)
);
/**/
SELECT Last_Editor, LuTr_LuId FROM master_file ORDER BY Last_Editor DESC;

SELECT count(Shp_Id) FROM master_file WHERE Csm_ConsigneeCode="015" OR Csm_ConsigneeCode="017";

ALTER TABLE consignment ADD Shp_Id VARCHAR(40);

/* Consignment file */
/* Going to add Shp_Id to assist with linking to Ocean */
DROP TABLE NIMS.consignment;
CREATE TABLE consignment(
  UniqueNo         	      	 VARCHAR(40) NOT NULL,
  Csm_Id                  	 VARCHAR(40) NOT NULL,
  Shp_Id                     VARCHAR(40),
  LuTr_LuId            	   	 VARCHAR(40),
  Csm_ConsignorCode       	 VARCHAR(40),
  Csm_ConsigneeCode          VARCHAR(40),
  Shp_Id_On_Carriage  		   VARCHAR(40),
  Csm_Status                 VARCHAR(40),
  PRIMARY KEY(Csm_Id)
);


CREATE TABLE cns_ds(
  Csm_Id                  VARCHAR(40),
  DC_Transit              VARCHAR(40),
  DC_High_Bay             VARCHAR(40),
  DC_Low_Bay              VARCHAR(40),
  CDC_Low_Bay             VARCHAR(40),
  CDC_Mixed_Lines         VARCHAR(40),
  PRIMARY KEY(Csm_Id)
);

DROP TABLE NIMS.cns_ds_sum;
CREATE TABLE cns_ds_sum(
  Shp_Id                  VARCHAR(40),
  DC_Transit              VARCHAR(40),
  DC_High_Bay             VARCHAR(40),
  DC_Low_Bay              VARCHAR(40),
  CDC_Low_Bay             VARCHAR(40),
  CDC_Mixed_Lines         VARCHAR(40),
  PRIMARY KEY(Shp_Id)
);



/* Inbound_TD file*/
DROP TABLE NIMS.target_dates;
CREATE TABLE target_dates(
	Shp_Id				        VARCHAR(40) NOT NULL,
  WeekNo				        VARCHAR(40),
  Consignee			        VARCHAR(40),
  UL_Number			        VARCHAR(40),
  Earliest_TD		      	DATE,
  Target_Date		      	DATE,
  Latest_TD			        DATE,
  Max_OTD			        	VARCHAR(40),
  Order_Spread	      	VARCHAR(40),
  PRIMARY KEY(Shp_Id)
  -- FOREIGN KEY (Shp_Id) REFERENCES master_file(Shp_Id)
);

DROP TABLE NIMS.au_des_receiving_capacity;
CREATE TABLE NIMS.au_des_receiving_capacity(
  pkey                  INTEGER AUTO_INCREMENT,
  BU                    VARCHAR(40),
  Sequence              VARCHAR(40),
  WEEK              VARCHAR(40),
  Day                   VARCHAR(40),
  HF                    VARCHAR(40),
  LF                    VARCHAR(40),
  DD                    VARCHAR(40),
  PRIMARY KEY(pkey)                    
);


DROP TABLE NIMS.au_cus_dec;
CREATE TABLE NIMS.au_cus_dec(
  Shp_Id                            VARCHAR(40) NOT NULL,
  Customs_Status                    VARCHAR(40),
  Customs_Status_Description        VARCHAR(40),
  Status_Date                       VARCHAR(40),
  ETA                               DATE,
  Carrier_Vessel                    VARCHAR(40),
  Ocean_Carrier_VOY                 VARCHAR(40),
  PRIMARY KEY(Shp_Id)                    
);

DROP TABLE archive;
CREATE TABLE NIMS.archive(
  id                         INTEGER AUTO_INCREMENT,
  Shp_Id                     VARCHAR(40) NOT NULL,
  MILKRUN			               VARCHAR(40),
  Unload_Date			           VARCHAR(40),
  Unload_Time			           VARCHAR(40),
  Dock_No			               VARCHAR(40),
  Delivery_Date		           VARCHAR(40),
  Delivery_Time		           VARCHAR(40),
  Last_Editor				         VARCHAR(40),
	Transport_Comment	         VARCHAR(200), 
  PRIMARY KEY(id)
);


DROP TABLE NIMS.manual_input_transport;
CREATE TABLE manual_input_transport(
  Shp_Id                     VARCHAR(40) NOT NULL,
  MILKRUN			               VARCHAR(40),
  Unload_Date			           DATE,
  Unload_Week                VARCHAR(40),
  Unload_Time			           VARCHAR(40),
  Dock_No			               VARCHAR(40),
  Delivery_Date		           DATE,
  Delivery_Time		           VARCHAR(40),
	Transport_Comment	         VARCHAR(200), 
  Last_Editor				         VARCHAR(40),
  PRIMARY KEY(Shp_Id)
);



DROP TABLE dc_outbound;
CREATE TABLE dc_outbound(
  Csm_Id                  VARCHAR(40),
  BU                      VARCHAR(40),
  Trailer_Number          VARCHAR(40),
  Dispatch_Number         VARCHAR(40),
  Dispatch_Note           VARCHAR(40),
  Trailer_Type            VARCHAR(40),
  Load_Time               VARCHAR(40),
  DC_Gate                 VARCHAR(40),
  Unload_Date             DATE,
  Seal_No                 VARCHAR(40),
  PRIMARY KEY(Csm_Id)
);


DROP TABLE NIMS.userlogs;
CREATE TABLE NIMS.userlogs(
	pkey			        	Integer AUTO_INCREMENT,
  userName		      	VARCHAR(40),
	action		          VARCHAR(100),
  value   	    	    VARCHAR(5000),
  row		      	      VARCHAR(500),
  dateChanged         DATETIME,
  PRIMARY KEY(pkey)
);



DROP TABLE usersinfo;
CREATE TABLE NIMS.usersinfo (
  username VARCHAR(45) NOT NULL,
  password VARCHAR(500) NOT NULL,
  fName VARCHAR(45) NULL,
  lName VARCHAR(45) NULL,
  level INTEGER(1) NOT NULL,
  PRIMARY KEY (username)
);
  

/*User initialisation: user:admin pw:pass*/
INSERT INTO NIMS.usersinfo(username,password,fName,lName, level)
VALUES("admin", "1a1dc91c907325c69271ddf0c944bc72", "Admini", "Strator", "1");


DROP TABLE NIMS.customs_status_code;
CREATE TABLE NIMS.customs_status_code(
  code              VARCHAR(3),
  status            VARCHAR(100),
  PRIMARY KEY(code)
);


/*
Base values for customs_status_code
*/
INSERT INTO NIMS.customs_status_code(code, status)
VALUES("001", "Submitted");
INSERT INTO NIMS.customs_status_code(code, status)
VALUES("003", "Sent to Customs");
INSERT INTO NIMS.customs_status_code(code, status)
VALUES("004", "Rec & Acknowledged by Authorities");
INSERT INTO NIMS.customs_status_code(code, status)
VALUES("005", "Held");
INSERT INTO NIMS.customs_status_code(code, status)
VALUES("006", "Cleared  ($YYY)");
INSERT INTO NIMS.customs_status_code(code, status)
VALUES("007", "Released (ATD)");
INSERT INTO NIMS.customs_status_code(code, status)
VALUES("101", "Conditional Release");



CREATE OR REPLACE VIEW TransportView AS
SELECT m.Shp_Id AS Ship_Id, 
m.LuTr_LuId AS LuTr_LuId,
m.LuTr_LutCode AS LuTr_LutCode,
m.LuTr_SealNo AS LuTr_SealNo,
m.Ccs_SumWeiGkg AS Ccs_SumWeiGkg,
m.Ccs_SumVolGroAct AS Ccs_SumVolGroAct,
m.IN_YARD_DATE AS In_Yard_Date,
m.Wharf_Availability AS Wharf_Availability,
m.Shp_DateStart AS ETD,
m.PRIO AS Prio,
n.DC AS 015_Consignments,
n.CDC AS 017_Consignments,
sum(d.DC_Transit) as 'DC_Transit',
sum(d.DC_High_Bay) as 'DC_High_Bay',
sum(d.DC_Low_Bay) as 'DC_Low_Bay',
sum(d.CDC_Low_Bay) as 'CDC_Low_Bay',
sum(d.CDC_Mixed_Lines) as 'CDC_Mixed_Lines'
FROM master_file m, consignment c, cns_ds d, dc_cdc_counter n
WHERE m.UniqueNo = c.UniqueNo
AND c.Csm_Id = d.Csm_Id
AND n.ship = m.Shp_Id
AND m.Shp_DateStart IS NOT NULL
GROUP BY m.Shp_Id;


#--------------------------------------------------------------
TRUNCATE TABLE cns_ds_sum;
INSERT INTO cns_ds_sum(Shp_Id, DC_Transit,DC_High_Bay,DC_Low_Bay,CDC_Low_Bay,CDC_Mixed_Lines)
SELECT c.Shp_Id,
sum(d.DC_Transit),
sum(d.DC_High_Bay),
sum(d.DC_Low_Bay),
sum(d.CDC_Low_Bay),
sum(d.CDC_Mixed_Lines)
FROM consignment c, cns_ds d
WHERE c.Csm_Id=d.Csm_Id
GROUP BY c.Shp_Id;

#--------------------------------------------------------------


  CREATE OR REPLACE VIEW StoreView AS
  SELECT m.LuTr_LuId AS LuTr_LuId,
  m.Shp_Id AS Shp_Id,
  m.Csm_ConsigneeCode AS Csm_ConsigneeCode,
  m.Csm_ConsignorCode AS Csm_ConsignorCode,
  m.LuTr_LutCode AS LuTr_LutCode,
  m.LuTr_SealNo AS LuTr_SealNo,
  m.Shipment_Type AS Shipment_Type,
  DATE_ADD(c.ETA , INTERVAL 21 DAY) AS LAST_DAY_DET,
  c.Customs_Status AS Customs_Status,
  c.Customs_Status_Description AS Customs_Status_Description,
  i.MILKRUN AS MILKRUN,
  i.Unload_Date AS Unload_Date,
  DATE_FORMAT(i.Unload_Date,"%x%v") AS Unload_Week,
  i.Unload_Time AS Unload_Time,
  i.Dock_No AS Dock_No
  FROM master_file m, manual_input_transport i, au_cus_dec c
  WHERE m.Shp_Id = i.Shp_Id
  AND m.Shp_Id = c.Shp_Id
  AND i.Unload_Date IS NOT NULL
  GROUP BY m.Shp_Id;


delete from dc_outbound where Unload_Date < now() - interval 60 DAY;


UPDATE dc_outbound 
SET BU='006' WHERE BU='6';

UPDATE dc_outbound 
SET BU='017' WHERE BU='17';

UPDATE dc_outbound 
SET BU='034' WHERE BU='34';

UPDATE dc_outbound 
SET BU='044' WHERE BU='44';



INSERT INTO archive(Shp_Id, MILKRUN, Unload_Date, Unload_Time, Dock_No, Delivery_Date, Delivery_Time, Shp_Id_On_Carriage_Status, Last_Editor)
SELECT Shp_Id, MILKRUN, Unload_Date, Unload_Time, Dock_No, Delivery_Date, Delivery_Time, Shp_Id_On_Carriage_Status, Last_Editor
FROM manual_input_transport
WHERE Shp_Id NOT IN(SELECT Shp_Id FROM master_file)
AND Unload_Date > now() + interval 90 DAY;



UPDATE master_file m, consignment c SET m.Shipment_Type="Low_Flow(China)" 
WHERE m.UniqueNo=c.UniqueNo 
AND (c.Csm_ConsignorCode="294" 
OR c.Csm_ConsignorCode="319");

UPDATE master_file m, consignment c SET m.Shipment_Type="High_Flow(China)" 
WHERE m.UniqueNo=c.UniqueNo 
AND (c.Csm_ConsignorCode="277" 
OR c.Csm_ConsignorCode="258");



TRUNCATE TABLE eventtest;
DROP TABLE eventtest;
CREATE TABLE eventtest(
  id  INTEGER AUTO_INCREMENT,
  s   VARCHAR(15),
  PRIMARY KEY(id)
 );


DROP EVENT hi;

CREATE EVENT hi
  ON SCHEDULE 
    EVERY 2 SECOND ENABLE
DO 
INSERT INTO eventtest(s)
VALUES("Something");


SHOW EVENTS;

DROP TABLE t;
CREATE TABLE t(
  title   VARCHAR(20),
  expired VARCHAR(20),
  amount  VARCHAR(20),
  PRIMARY KEY(amount)
);
TRUNCATE TABLE t;

LOAD DATA LOCAL INFILE '//retauso-nt8006/NIMS/Files/SAVE/FileName.csv' 
INTO TABLE t 
FIELDS TERMINATED BY ',' 
IGNORE 1 ROWS
(title, expired, @dummy, amount);



/*CURRENT*/
CREATE OR REPLACE VIEW AU_Detention_Cost AS
SELECT 
m.Csm_ConsigneeCode AS BU,
DATE_FORMAT(i.Unload_Date,"%x%v") AS WEEK,
SUM(IF((((DATEDIFF(IF(i.Unload_Date IS NOT NULL OR i.Unload_Date<>"", i.Unload_Date, CURRENT_DATE), IF(c.ETA IS NOT NULL,c.ETA,m.ShpRou_RecPldArrDate)))-21) * 120) >= 0,(((DATEDIFF(IF(i.Unload_Date IS NOT NULL OR i.Unload_Date<>"", i.Unload_Date, CURRENT_DATE), IF(c.ETA IS NOT NULL,c.ETA,m.ShpRou_RecPldArrDate)))-21) * 120),0)) AS DET_COST
FROM master_file m
LEFT JOIN manual_input_transport i
ON m.Shp_Id = i.Shp_Id
LEFT JOIN au_cus_dec c
ON m.Shp_Id = c.Shp_Id
WHERE i.Unload_Date IS NOT NULL
GROUP BY BU, WEEK;
/*CURRENT*/





