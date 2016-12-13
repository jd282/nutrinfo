\COPY Users(user_id, user_email, user_password, user_firstname, user_lastname, user_dob, user_registered) FROM 'data/Users.dat' WITH DELIMITER ',' NULL '' CSV
\COPY Location(locationID, name) FROM 'data/Location.dat' WITH DELIMITER ',' NULL '' CSV
\COPY Restaurant(restaurantID, locationID, name) FROM 'data/Restaurant.dat' WITH DELIMITER ',' NULL '' CSV
\COPY timesOpen(restaurantID, locationID, timeOpen, timeClose, dayOfWeek) FROM 'data/timesOpen.dat' WITH DELIMITER ',' NULL '' CSV
\COPY Food(foodID, name, calories, totalFat, transFat, saturatedFat, cholesterol, sodium, carbs, fiber, sugars, protein, vitaminA, vitaminC, vitaminD, calcium, iron) FROM 'data/Food.dat' WITH DELIMITER ',' NULL '' CSV
\COPY Serves(restaurantID, locationID, foodID, price) FROM 'data/Serves.dat' WITH DELIMITER ',' NULL '' CSV
\COPY Student(student_userid, DoB, sex, height, weight, foodPoints) FROM 'data/Student.dat' WITH DELIMITER ',' NULL '' CSV
\COPY Ate(ate_userid, foodID, eatDate) FROM 'data/Ate.dat' WITH DELIMITER ',' NULL '' CSV
\COPY Goals(goals_userid, maxCals, maxFat, maxSug, maxSodium, maxProtein, minCals, minFat, minSug, minSodium, minProtein) FROM 'data/Goals.dat' WITH DELIMITER ',' NULL '' CSV

