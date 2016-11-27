CREATE TABLE Users
(
user_id SERIAL PRIMARY KEY,
user_email VARCHAR(100) NOT NULL UNIQUE,
user_password VARCHAR(64) NOT NULL,
user_firstname VARCHAR(50) NOT NULL,
user_lastname VARCHAR(50) NOT NULL,
user_registered TIMESTAMP NOT NULL
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
dayOfWeek VARCHAR(10), 
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
price FLOAT NOT NULL CHECK(price>0.0), --Assuming all food has a cost
PRIMARY KEY(restaurantID, locationID, foodID)
);

CREATE TABLE Student
(
studentNetID VARCHAR(10) NOT NULL PRIMARY KEY,
fullName VARCHAR(256) NOT NULL, 
DoB DATE NOT NULL,
sex VARCHAR(10) NOT NULL, 
height INTEGER NOT NULL CHECK(height > 0 AND height <= 96),
weight INTEGER NOT NULL CHECK(weight > 0),
foodPoints FLOAT NOT NULL CHECK(foodPoints >= 0 AND foodPoints <= 3300)
);

CREATE TABLE Ate
(
studentNetID VARCHAR(10) NOT NULL,
foodID INTEGER NOT NULL REFERENCES Food(FoodID),
eatDate DATE NOT NULL
);

CREATE TABLE Goals
(
studentNetID VARCHAR(10) NOT NULL, 
maxCals INTEGER CHECK(maxCals IS NULL OR maxCals >= 0),
maxFat INTEGER CHECK(maxFat IS NULL OR maxFat >= 0),
maxSug INTEGER CHECK(maxSug IS NULL OR maxSug >= 0),
maxSodium INTEGER CHECK(maxSodium IS NULL OR maxSodium >= 0),
maxProtein INTEGER CHECK(maxProtein IS NULL OR maxProtein >= 0),
minCals INTEGER CHECK(minCals IS NULL OR (minCals >= 0)),
minFat INTEGER CHECK(minFat IS NULL OR (minFat >= 0)),
minSug INTEGER CHECK(minSug IS NULL OR (minSug >= 0 )),
minSodium INTEGER CHECK(minSodium IS NULL OR (minSodium >= 0)),
minProtein INTEGER CHECK(minProtein IS NULL OR (minProtein >= 0))
);

CREATE TABLE Warnings
(
studentNetID VARCHAR(10) NOT NULL REFERENCES Student(studentNetID),
warningDate DATE NOT NULL,
message VARCHAR(70) NOT NULL CHECK(message IN ('You have exceeded your maximum daily calorie intake! :(', 'You have not reached your minimum daily calorie intake yet!'))
);

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
