
select 'All case types';
select id, name from civicrm_case_type cct;
select id, name, component_id from civicrm_case_type cct,  brisskit_case_type_mappings bctm where bctm.case_type_id = cct.id;

select 'case types without mapping (all should be templates)';
select id, name from civicrm_case_type cct where not exists ( select null from brisskit_case_type_mappings bctm where bctm.case_type_id = cct.id);

select 'orphan case type mappings';
select * from brisskit_case_type_mappings bctm where not exists ( select null from  civicrm_case_type cct  where bctm.case_type_id = cct.id);

select 'cases without mapping';
select id, subject, case_type_id from civicrm_case cc where not exists ( select null from brisskit_case_mappings bcm where bcm.case_id = cc.id);

select 'orphan case mappings';
select * from brisskit_case_mappings bcm where not exists ( select null from  civicrm_case cc  where bcm.case_id = cc.id);

select 'Case types with mismatching mapping (check name against component id)';
select id, name, component_id from civicrm_case_type cct, brisskit_case_type_mappings bctm where bctm.case_type_id = cct.id
and name like 'study_%'
and bctm.component_id = 1;

select id, name, component_id from civicrm_case_type cct, brisskit_case_type_mappings bctm where bctm.case_type_id = cct.id
and name not like 'study_%'
and bctm.component_id = 2;





select 'All recruitments';
select cc.id, subject, bcm.component_id, cc.case_type_id, name, bctm.component_id
from
  civicrm_case cc,
  civicrm_case_type cct,
  brisskit_case_type_mappings bctm,
  brisskit_case_mappings bcm
where 
  bctm.case_type_id = cct.id and
  bcm.case_id = cc.id and
  cct.id = cc.case_type_id;

select 'Recruitmentsi where mapping doesnt agree with that of the parent case type';
select cc.id, subject, bcm.component_id, cc.case_type_id, name, bctm.component_id
from
  civicrm_case cc,
  civicrm_case_type cct,
  brisskit_case_type_mappings bctm,
  brisskit_case_mappings bcm
where 
  bctm.case_type_id = cct.id and
  bcm.case_id = cc.id and
  cct.id = cc.case_type_id and bctm.component_id != bcm.component_id

