delimiter $$

create trigger new_animal_trigger before insert on animal
for each row
begin
	set new.age = year(current_date) - new.birth_year;
end$$

delimiter ;
