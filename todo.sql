set names utf8;
/*
alter table events add ResponsibleContacts VARCHAR(255) NULL;

alter table events add StartYear INT(11) DEFAULT -1 AFTER EventTime;
alter table events add StartMonth INT(11) DEFAULT -1 AFTER StartYear;
alter table events add StartWeekDay INT(11) DEFAULT -1 AFTER StartMonth;
alter table events add StartDay INT(11) DEFAULT -1 AFTER StartWeekDay;
alter table events add StartHour INT(11) DEFAULT -1 AFTER StartDay;
alter table events add StartMinute INT(11) DEFAULT -1 AFTER StartHour;

alter table events add FinishYear INT(11) DEFAULT -1 AFTER StartMinute;
alter table events add FinishMonth INT(11) DEFAULT -1 AFTER FinishYear;
alter table events add FinishWeekDay INT(11) DEFAULT -1 AFTER FinishMonth;
alter table events add FinishDay INT(11) DEFAULT -1 AFTER FinishWeekDay;
alter table events add FinishHour INT(11) DEFAULT -1 AFTER FinishDay;
alter table events add FinishMinute INT(11) DEFAULT -1 AFTER FinishHour;

create table eventdates(
  idEventDate int(11) not null primary key auto_increment,
  EventID int(11) not null,
  EventDate date not null,
  constraint fk_eventdates_EventID foreign key(EventID)
  references events(idEvent)
);

 update departments set FunctionDescription="Факультети ЗНУ" 
 where idDepartment in (92,93,94,95,96,97,98,99,100,101,102,103,104);

 
 update departments set FunctionDescription="Коледжі ЗНУ" 
 where idDepartment in (82,83);

 alter table events add DocumentID INT(11) NULL AFTER ResponsibleContacts;
 
 alter table invited change DeptID DeptID int(11) null;
 alter table invited drop foreign key `fk2_invited_DeptID`;

alter table events change EventTime DateSmartField varchar(255) NULL;

update eventtypes set EventTypeName="Регіональні" where EventTypeName="Регіональний";
update eventtypes set EventTypeName="Всеукраїнські" where EventTypeName="Всеукраїнський";
update eventtypes set EventTypeName="Міжнародні" where EventTypeName="Міжнародний";

update eventtypes set EventTypeStyle="info" where EventTypeName="Загальноуніверситетські";
update eventtypes set EventTypeStyle="red" where EventTypeName="Факультетські";
update eventtypes set EventTypeStyle="warning" where EventTypeName="Регіональні";
update eventtypes set EventTypeStyle="success" where EventTypeName="Всеукраїнські";
update eventtypes set EventTypeStyle="important" where EventTypeName="Міжнародні";

update eventkinds set EventKindStyle="warning" where EventKindName="Прийом гостей";
update eventkinds set EventKindStyle="important" where EventKindName="Прийом іноземних гостей";
update eventkinds set EventKindStyle="inverse" where EventKindName="Наукові";
update eventkinds set EventKindStyle="info" where EventKindName="Виховні";
update eventkinds set EventKindStyle="info" where EventKindName="Культурно-масові";
update eventkinds set EventKindStyle="success" where EventKindName="Спортивні";
update eventkinds set EventKindStyle="red" where EventKindName="Студентське самоврядування";




alter table events add StartTime time default '08:00:00' AFTER FinishMinute;
alter table events add FinishTime time default '17:00:00' AFTER StartTime;
alter table events drop StartYear;
alter table events drop StartMonth;
alter table events drop StartWeekDay;
alter table events drop StartDay;
alter table events drop StartHour;
alter table events drop StartMinute;
alter table events drop FinishYear;
alter table events drop FinishMonth;
alter table events drop FinishWeekDay;
alter table events drop FinishDay;
alter table events drop FinishHour;
alter table events drop FinishMinute;
alter table events change StartTime StartTime time null default '08:00:00';
alter table events change FinishTime FinishTime time null default '17:00:00';

alter table eventorganizers drop foreign key `fk2_eventorganizers_DeptID`;

 alter table invited add Seets int null;

*/
 alter table events change DocumentID FileID INT(11) NULL AFTER ResponsibleContacts;