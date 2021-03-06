CREATE TABLE Users
(
user_id SERIAL PRIMARY KEY,
user_email VARCHAR(100) NOT NULL UNIQUE,
user_password VARCHAR(64) NOT NULL,
user_firstname VARCHAR(50) NOT NULL,
user_lastname VARCHAR(50) NOT NULL,
user_dob DATE NOT NULL,
user_registered TIMESTAMP NOT NULL,
user_sex VARCHAR(20) NOT NULL,
user_height INTEGER NOT NULL CHECK(user_height > 0 AND user_height <= 96), --measured in inches
user_weight INTEGER NOT NULL CHECK(user_weight > 0 AND user_weight <= 1500) --measured in pounds
);

CREATE TABLE Location
(
locationID INTEGER NOT NULL PRIMARY KEY, 
name VARCHAR(256) NOT NULL
);

CREATE TABLE Restaurant
(
restaurantID INTEGER NOT NULL PRIMARY KEY,
locationID INTEGER NOT NULL REFERENCES Location(locationID),
name VARCHAR(256) NOT NULL
);

CREATE TABLE timesOpen
(
restaurantID INTEGER NOT NULL REFERENCES Restaurant(restaurantID),
locationID INTEGER NOT NULL REFERENCES Location(locationID),
timeOpen TIME, 
timeClose TIME CHECK((timeOpen IS NULL AND timeClose IS NULL) OR (timeOpen IS NOT NULL AND timeClose IS NOT NULL)),
dayOfWeek VARCHAR(10) NOT NULL, 
PRIMARY KEY(restaurantID, locationID)
);

CREATE TABLE Food
(
foodID INTEGER NOT NULL PRIMARY KEY,
name VARCHAR(256) NOT NULL,
calories INTEGER NOT NULL CHECK(calories>=0), 
totalFat INTEGER NOT NULL CHECK(totalFat>=0), --measured in grams
transFat INTEGER NOT NULL CHECK(transFat>=0), --measured in grams
saturatedFat INTEGER NOT NULL CHECK(saturatedFat>=0), --measured in grams
cholesterol INTEGER NOT NULL CHECK(cholesterol>=0), --measured in milligrams
sodium INTEGER NOT NULL CHECK(sodium>=0), --measured in milligrams
carbs INTEGER NOT NULL CHECK(carbs>=0), --measured in grams
fiber INTEGER NOT NULL CHECK(fiber>=0), --measured in grams
sugars INTEGER NOT NULL CHECK(sugars>=0), --measured in grams
protein INTEGER NOT NULL CHECK(protein>=0), --measured in grams
vitaminA INTEGER NOT NULL CHECK(vitaminA>=0), --measured as a percentage
vitaminC INTEGER NOT NULL CHECK(vitaminC>=0), --measured as a percentage 
vitaminD INTEGER NOT NULL CHECK(vitaminD>=0), --measured as a percentage
calcium INTEGER NOT NULL CHECK(calcium>=0), --measured as a percentage
iron INTEGER NOT NULL CHECK(iron>=0) --measured as a percentage 
);

CREATE TABLE Serves
(
restaurantID INTEGER NOT NULL REFERENCES Restaurant(restaurantID),
locationID INTEGER NOT NULL REFERENCES Location(locationID),
foodID INTEGER NOT NULL REFERENCES Food(foodID),
PRIMARY KEY(restaurantID, locationID, foodID)
);

CREATE TABLE Ate
(
ate_userid INTEGER NOT NULL REFERENCES Users(user_id),
foodID INTEGER NOT NULL REFERENCES Food(foodID),
eatDate TIMESTAMP(20) NOT NULL
);

CREATE TABLE Goals
(
goals_userid INTEGER NOT NULL REFERENCES Users(user_id),
maxCals INTEGER CHECK(maxCals >= 0),
maxFat INTEGER CHECK(maxFat >= 0),
maxSug INTEGER CHECK(maxSug >= 0),
maxSodium INTEGER CHECK(maxSodium >= 0),
maxProtein INTEGER CHECK(maxProtein >= 0),
minCals INTEGER CHECK(minCals >= 0),
minFat INTEGER CHECK(minFat >= 0),
minSug INTEGER CHECK(minSug >= 0 ),
minSodium INTEGER CHECK(minSodium >= 0),
minProtein INTEGER CHECK(minProtein >= 0)
);

/*
CREATE FUNCTION TF_food_ref() RETURNS TRIGGER AS $$
BEGIN
	--this trigger will alert the user if their calorie intake is above max goal or below min goal
	IF ((SELECT SUM(Food.calories)  
		FROM Ate, Food
		WHERE Ate.Foodid = Food.Foodid AND ate.studentNetID = NEW.studentNetID AND NEW.eatDate = ate.eatDate)
		 <
		(SELECT Goals.minCals
		FROM Goals
		WHERE NEW.studentNetID = Goals.studentNetId))
		
	THEN INSERT INTO Warnings VALUES(NEW.studentNetID, NEW.eatDate, 'You have not reached your minimum daily calorie intake yet!');
	END IF; 
	
	IF ((SELECT SUM(Food.calories)  
		FROM Ate, Food
		WHERE Ate.Foodid = Food.Foodid AND ate.studentNetID = NEW.studentNetID AND NEW.eatDate = ate.eatDate)
		 >
		(SELECT Goals.maxCals
		FROM Goals
		WHERE NEW.studentNetID = Goals.studentNetId))
		
	THEN INSERT INTO Warnings VALUES(NEW.studentNetID, NEW.eatDate, 'You have exceeded your maximum daily calorie intake! :(');
	END IF; 
	

	RETURN NEW;				
	
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER TG_food_ref
  AFTER INSERT OR UPDATE ON Ate
  FOR EACH ROW
  EXECUTE PROCEDURE TF_food_ref();
*/
