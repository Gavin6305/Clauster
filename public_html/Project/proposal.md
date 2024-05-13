# Project Name: Arcade Project
## Project Summary: This project will create a simple Arcade with scoreboards and competitions based on the implemented game.
## Github Link: https://github.com/GPintoNJIT/IT202--003-/tree/prod/public_html/Project
## Project Board Link: https://github.com/GPintoNJIT/IT202--003-/projects/1
## Website Link: https://gkp2-prod.herokuapp.com/Project/login.php
## Your Name: Gavin Pinto

<!--
### Line item / Feature template (use this for each bullet point)
#### Don't delete this

- [ ] \(mm/dd/yyyy of completion) Feature Title (from the proposal bullet point, if it's a sub-point indent it properly)
  -  List of Evidence of Feature Completion
    - Status: Pending (Completed, Partially working, Incomplete, Pending)
    - Direct Link: (Direct link to the file or files in heroku prod for quick testing (even if it's a protected page))
    - Pull Requests
      - PR link #1 (repeat as necessary)
    - Screenshots
      - Screenshot #1 (paste the image so it uploads to github) (repeat as necessary)
        - Screenshot #1 description explaining what you're trying to show
### End Line item / Feature Template
--> 
### Proposal Checklist and Evidence

- Milestone 1
- [x] \(10/25/2021) User will be able to register a new account
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://gkp2-prod.herokuapp.com/Project/register.php
      - Pull Requests
        - PR link #1 https://github.com/GPintoNJIT/IT202--003-/pull/9
      - Screenshots
        - Screenshot #1 https://user-images.githubusercontent.com/89927115/142272752-4639ca69-a4bb-4b15-bac2-e35a8eddf204.png
          - Screenshot #1 Form fields and email validation
        - Screenshot #2 https://user-images.githubusercontent.com/89927115/142278360-0e4173c3-770a-44da-84b5-0d65ce75d4f3.png
          - Screenshot #2 Email is required
        - Screenshot #3 https://user-images.githubusercontent.com/89927115/142273551-12fcb29e-d468-43f0-8ffe-003f898cb3e0.png
          - Screenshot #3 Username is required
        - Screenshot #4 https://user-images.githubusercontent.com/89927115/142273820-9d560066-55f7-4ff0-b93c-4473a9f4cfbc.png
          - Screenshot #4 Confirm passwords match
        - Screenshot #5 https://user-images.githubusercontent.com/89927115/142274127-9e3f89a6-6f9c-482a-8397-71bda9306fd8.png
          - Screenshot #5 Users table and passwords must be matched
        - Screenshot #6 https://user-images.githubusercontent.com/89927115/142274368-eafe7f3d-a6b5-4154-825f-de6c40a89464.png
          - Screenshot #6 Email must be unique
        - Screenshot #7 https://user-images.githubusercontent.com/89927115/142274525-708c383a-e9ba-480b-8532-fde86dee1311.png
          - Screenshot #7 Username must be unique
  - [x] \(10/25/2021) User will be able to login to their account (given they enter the correct credentials)
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://gkp2-prod.herokuapp.com/Project/login.php
      - Pull Requests
        - PR link #1 https://github.com/GPintoNJIT/IT202--003-/pull/9
      - Screenshots
        - Screenshot #1 https://user-images.githubusercontent.com/89927115/142280451-cce4e863-0953-4b83-92a0-93212db427ca.png
          - Screenshot #1 User can login with username
        - Screenshot #2 https://user-images.githubusercontent.com/89927115/142280670-985002e6-0b3b-423f-b800-4839ae85d05a.png
          - Screenshot #2 User can login with email
        - Screenshot #3 https://user-images.githubusercontent.com/89927115/142281362-ad48825d-96f7-469d-80e6-2cc4d2cbfa07.png
          - Screenshot #3 Password is required
        - Screenshot #4 https://user-images.githubusercontent.com/89927115/142281779-1b1b0f3f-db53-4bed-804a-ea4b2a0e3aca.png
          - Screenshot #4 Friendly error messages when account doesn't exist or password is wrong (I was told that the messages appear after login because of browser cache)
        - Screenshot #5 https://user-images.githubusercontent.com/89927115/142282331-406e4d09-d5bd-41d0-accd-8431f8e8e142.png
          - Screenshot #5 Logging in should fetch the user’s details (and roles) and save them into the session.
        - Screenshot #6 https://user-images.githubusercontent.com/89927115/142282622-6f3a2e47-6a20-4066-b167-043879ed45eb.png
          - Screenshot #6 User is directed to landing page upon login, and it is a protected page. (Pressing the back button after logging out prompts the "You must be logged in" message but it shows after login because of browser cache)
  - [x] \(10/25/2021) User will be able to logout
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://gkp2-prod.herokuapp.com/Project/logout.php
      - Pull Requests
        - PR link #1 https://github.com/GPintoNJIT/IT202--003-/pull/9
      - Screenshots
        - Screenshot #1 https://user-images.githubusercontent.com/89927115/142284776-67753c73-0e31-40a6-941a-6bcaabf5bc77.png
          - Screenshot #1 Pressing logout goes to the login page
        - Screenshot #2 https://user-images.githubusercontent.com/89927115/142285382-ebc919bd-08c0-4b08-b2e3-b6c2c92b937f.png
          - Screenshot #2 Pressing the back button just goes to the login page, but after logging back in, the message is seen, I was told this was because of browser cache.
        - Screenshot #3 https://user-images.githubusercontent.com/89927115/142295022-4f26ebaf-1f8c-4790-a6ff-30a3747d1bb2.png
          - Screenshot #3 An attempt was made to show the successful logout message by using the flash method after the session is reset, but it did not work after the browser cache issue started happening.
  - [x] \(10/25/2021) Basic security rules implemented
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://gkp2-prod.herokuapp.com/Project/login.php
      - Pull Requests 
        - PR link #1 https://github.com/GPintoNJIT/IT202--003-/pull/10
      - Screenshots
        - Screenshot #1 https://user-images.githubusercontent.com/89927115/142296063-5b737442-deb8-491f-ad7f-2d22f76fb34e.png
          - Screenshot #1 Function to check if user is logged in and is called on appropriate pages that only allow logged in users
        - Screenshot #2 https://user-images.githubusercontent.com/89927115/142296564-ea133575-805c-4df8-a98c-003375005927.png
          - Screenshot #2 Roles table
  - [x] \(11/15/2021 of completion) Basic Roles implemented
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://gkp2-prod.herokuapp.com/Project/login.php
      - Pull Requests
        - PR link #1 https://github.com/GPintoNJIT/IT202--003-/pull/38
      - Screenshots 
        - Screenshot #1 https://user-images.githubusercontent.com/89927115/142297309-d61393d9-20a4-4ab5-a89d-1d5fa5f95ab5.png
          - Screenshot #1 Roles table
        - Screenshot #2 https://user-images.githubusercontent.com/89927115/142297416-227bf646-be4f-44e8-940e-cc728efbed85.png
          - Screenshot #2 User roles table
        - Screenshot #3 https://user-images.githubusercontent.com/89927115/142297647-0ad4aa86-9092-45df-b38e-7648e5fe335f.png
          - Screenshot #3 A function to check if a user has a specific role
  - [x] \(11/15/2021) Site should have basic styles/theme applied; everything should be styled
    -  List of Evidence of Feature Completion
      - Status: Complete
      - Direct Link: https://gkp2-prod.herokuapp.com/Project/login.php
      - Pull Requests
        - PR link #1 https://github.com/GPintoNJIT/IT202--003-/pull/35
      - Screenshots
        - Screenshot #1 https://user-images.githubusercontent.com/89927115/142298641-452efa9c-8595-478f-8673-196efbf6ba78.png
          - Screenshot #1 Styling for website
  - [x] \(11/15/2021) Any output messages/errors should be “user friendly”
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://gkp2-prod.herokuapp.com/Project/login.php
      - Pull Requests
        - PR link #1 https://github.com/GPintoNJIT/IT202--003-/pull/35
      - Screenshots
        - Screenshot #1 https://user-images.githubusercontent.com/89927115/142299163-7ed6004f-309b-4d0e-b6ee-b070af7b963b.png
          - Screenshot #1 User friendly errors
        - Screenshot #2 https://user-images.githubusercontent.com/89927115/142299288-035c77c5-419a-4d1a-98f7-d2fca0e34952.png
          - Screenshot #2 User friendly errors

    I could not do these last 2 items because I tried registering another user, and it worked, as seen in the table below:
    https://user-images.githubusercontent.com/89927115/142338073-aca6de2d-7d4a-49e4-be46-b260667f5650.png
    However, I tried logging in to see my profile, but when I pressed login, the fields just cleared and it took me to the login page again, like nothing happened. I thought it was a database issue, so I reloaded the database in VS code, and then I visited init_db.php, and the users table had an error, as well as the last table.
    Init_db.php
    https://user-images.githubusercontent.com/89927115/142338954-7134e312-2d98-4b7d-9962-c9bf6940dfd9.png
    I tried reloading everything in VS code, including the database, and going back to init_db.php and the login page and the same issue still remained. I also could not find anything in the code that would prevent a successful login with the right username and password.

  - [ ] \(mm/dd/yyyy of completion) User will be able to see their profile
    -  List of Evidence of Feature Completion
      - Status: Pending (Completed, Partially working, Incomplete, Pending)
      - Direct Link: (Direct link to the file or files in heroku prod for quick testing (even if it's a protected page))
      - Pull Requests
        - PR link #1 (repeat as necessary)
      - Screenshots
        - Screenshot #1 (paste the image so it uploads to github) (repeat as necessary)
          - Screenshot #1 description explaining what you're trying to show
  - [ ] \(mm/dd/yyyy of completion) User will be able to edit their profile
    -  List of Evidence of Feature Completion
      - Status: Pending (Completed, Partially working, Incomplete, Pending)
      - Direct Link: (Direct link to the file or files in heroku prod for quick testing (even if it's a protected page))
      - Pull Requests
        - PR link #1 (repeat as necessary)
      - Screenshots
        - Screenshot #1 (paste the image so it uploads to github) (repeat as necessary)
          - Screenshot #1 description explaining what you're trying to show
- Milestone 2
<table><tr><td>Milestone 2</td></tr><tr><td><table><tr><td>F1 - Pick a simple game to implement, anything that generates a score that’s more advanced than a simple random number generator (may build off of a sample from the site shared in class) (2021-11-29)</td></tr><tr><td>Status: complete</td></tr><tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/game.php](https://gkp2-prod.herokuapp.com/Project/game.php)</p></td></tr><tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/66](https://github.com/GPintoNJIT/IT202--003-/pull/66)</p></td></tr><tr><td><table><tr><td>F1 - What game will you be doing?<tr><td>Status: completed</td></tr><tr><td><img width="600px" src="https://user-images.githubusercontent.com/89927115/143975017-0c281b78-f435-4909-9dfe-baecc344000e.png"><p>A game called Clauster.</td></tr></td></tr></table></td></tr><tr><td><table><tr><td>F1 - Briefly describe it.<tr><td>Status: completed</td></tr><tr><td><img width="600px" src="https://user-images.githubusercontent.com/89927115/143975017-0c281b78-f435-4909-9dfe-baecc344000e.png"><p>It is a game where the player resides in the middle of the screen and enemies emerge from the borders of the screen, and the player will use their mouse to aim in the direction that they want to shoot, to prevent the enemies from surrounding them. Right now, the game file is a placeholder that generates a play area where the player presses on the W key to win the game.</td></tr></td></tr></table></td></tr><table><tr><td>F2 - The system will save the user’s score at the end of the game if the user is logged in (2021-12-06)</td></tr><tr><td>Status: complete</td></tr><tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/game.php](https://gkp2-prod.herokuapp.com/Project/game.php)</p></td></tr><tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/70](https://github.com/GPintoNJIT/IT202--003-/pull/70)</p></td></tr><tr><td><table><tr><td>F2 - There should be a scores table (id, user_id, score, created)<tr><td>Status: completed</td></tr><tr><td><img width="600px" src="https://user-images.githubusercontent.com/89927115/144873750-e976d3aa-0c74-4a25-8085-3fae7e81fd32.png"><p>There is a scores table to record the scores of the user.</td></tr></td></tr></table></td></tr><tr><td><table><tr><td>F2 - Each received score is a new entry (scores will not be updated)<tr><td>Status: completed</td></tr><tr><td><img width="600px" src="https://user-images.githubusercontent.com/89927115/144873750-e976d3aa-0c74-4a25-8085-3fae7e81fd32.png"><p>Each score is a new entry.</td></tr></td></tr></table></td></tr><table><tr><td>F3 - The user will be able to see their last 10 scores (2021-12-06)</td></tr><tr><td>Status: complete</td></tr><tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/profile.php](https://gkp2-prod.herokuapp.com/Project/profile.php)</p></td></tr><tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/71](https://github.com/GPintoNJIT/IT202--003-/pull/71)</p></td></tr><tr><td><table><tr><td>F3 - Show on their profile page<tr><td>Status: completed</td></tr><tr><td><img width="600px" src="https://user-images.githubusercontent.com/89927115/144910059-129ae4be-449a-415c-a94d-16cee84ffb3b.png"><p>The last 10 scores are shown on the profile page.</td></tr></td></tr></table></td></tr><tr><td><table><tr><td>F3 - Ordered by most recent<tr><td>Status: completed</td></tr><tr><td><img width="600px" src="https://user-images.githubusercontent.com/89927115/144910059-129ae4be-449a-415c-a94d-16cee84ffb3b.png"><p>The scores are ordered by most recent.</td></tr></td></tr></table></td></tr><table><tr><td>F4 - Create functions that output the following scoreboards (this will be used later) (2021-12-06)</td></tr><tr><td>Status: complete</td></tr><tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/profile.php](https://gkp2-prod.herokuapp.com/Project/profile.php)</p></td></tr><tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/72](https://github.com/GPintoNJIT/IT202--003-/pull/72)</p></td></tr><tr><td><table><tr><td>F4 - Top 10 Weekly, top 10 Monthly, and top 10 Lifetime. Scoreboards should show no more than 10 results; if there are no results a proper message should be displayed (i.e., “No [time period] scores to display”)<tr><td>Status: completed</td></tr><tr><td><img width="600px" src="https://user-images.githubusercontent.com/89927115/144911171-837b328e-1e88-433b-80cc-bbc98dd27941.png"><p>This function takes a string value for the duration (weekly, monthly, or lifetime), and it gets the top 10 scores based on the duration using the LIMIT command in the query passed to the user's table in the SQL server.</td></tr></td></tr></table></td></tr></td></tr></table>
- Milestone 3
<table>
<tr><td>Milestone 3</td></tr><tr><td>
<table>
<tr><td>F1 - Users will have points associated with their account. (2021-12-13)</td></tr>
<tr><td>Status: complete</td></tr>
<tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/profile.php](https://gkp2-prod.herokuapp.com/Project/profile.php)</p></td></tr>
<tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/90](https://github.com/GPintoNJIT/IT202--003-/pull/90)</p></td></tr>
<tr><td>
<table>
<tr><td>F1 - Alter the User table to include points with a default of 0.</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/145897977-7b66bb81-3584-45a5-85ad-a4c66406a712.png">
<p>The Users table was altered to include a column called Points, that stores an int value with a default of 0.</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F1 - Points should show on their profile page</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146651246-532be022-9903-47c2-a49c-58cf8a06cae0.png">
<p>Points are shown on the user's profile page.</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<table>
<tr><td>F2 - Create a PointsHistory table (id, user_id, point_change, reason, created) (2021-12-13)</td></tr>
<tr><td>Status: complete</td></tr>
<tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/profile.php](https://gkp2-prod.herokuapp.com/Project/profile.php)</p></td></tr>
<tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/91](https://github.com/GPintoNJIT/IT202--003-/pull/91)</p></td></tr>
<tr><td>
<table>
<tr><td>F2 - Create a PointsHistory table</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146126575-ffb5523a-8974-4ad9-aae8-e7e962bd71ec.png">
<p>Added the PointsHistory table with the required columns.</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F2 - Any new entry should update the user’s points value (do not update the User points column directly)</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146126855-b4398f92-3cf3-4b0b-997d-c6a370d15d13.png">
<p>This function updates the users points by summing all the point changes in the PointsHistory table.</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<table>
<tr><td>F3 - Competitions table should have the following columns (id, name, created, duration, expires (now + duration), current_reward, starting_reward, join_fee, current_participants, min_participants, paid_out (boolean), min_score, first_place_per, second_place_per, third_place_per, cost_to_create, modified) (2021-12-14)</td></tr>
<tr><td>Status: complete</td></tr>
<tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/login.php](https://gkp2-prod.herokuapp.com/Project/login.php)</p></td></tr>
<tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/92](https://github.com/GPintoNJIT/IT202--003-/pull/92)</p></td></tr>
<tr><td>
<table>
<tr><td>F3 - Add Competitions table</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146080132-af3c7fcb-8554-4420-967d-13204ff899c1.png">
<p>Added the competitions table with the required columns shown on the left.</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<table>
<tr><td>F4 - Will need an association table CompetitionParticipants (id, comp_id, user_id, created) (2021-12-14)</td></tr>
<tr><td>Status: complete</td></tr>
<tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/login.php](https://gkp2-prod.herokuapp.com/Project/login.php)</p></td></tr>
<tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/93](https://github.com/GPintoNJIT/IT202--003-/pull/93)</p></td></tr>
<tr><td>
<table>
<tr><td>F4 - Add CompetitionParticipants table</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146125701-17836bd9-6362-4458-aa87-b704d0ed022c.png">
<p>Added the association table CompetitionParticipants with the required columns.</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F4 - Comp_id and user_id should be a composite unique key (user can only join a competition once)</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146127153-d0d8ace7-4fa9-47ed-868c-b8cb0c8dfca5.png">
<p>Comp_id and user_id are a composite unique key, because a user can only join a competition once.</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<table>
<tr><td>F5 - User will be able to create a competition (2021-12-16)</td></tr>
<tr><td>Status: complete</td></tr>
<tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/create_competition.php](https://gkp2-prod.herokuapp.com/Project/create_competition.php)</p></td></tr>
<tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/94](https://github.com/GPintoNJIT/IT202--003-/pull/94)</p></td></tr>
<tr><td>
<table>
<tr><td>F5 - Competitions will start at 1 point (reward)</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146652698-a977ce6f-b63a-4888-8517-9bbfc0a0b367.png">
<p>All competitions start at 1 point, which is the starting reward.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F5 - User sets a name for the competition, user determines % given for 1st, 2nd, and 3rd place winners  (combination must be equal to 100% (no more, no less)), user determines if it’s free to join or the cost to join (min 0 for free), user determines the duration of the competition (in days), user can determine the minimum score to qualify (min 0), user determines minimum participants for payout (min 3)</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146652651-535535b7-3eff-4126-9532-a936ca0f4adf.png">
<p>User can set all of the required fields of the competition.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F5 - Show any user friendly error messages</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146652829-6321ca2b-9c7f-4a3a-b39a-d53aa84a2858.png">
<p>User friendly error messages are shown.</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F5 - Show user friendly confirmation message that competition was created</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146652890-a20ac8bb-0cf6-4d3e-9fac-b9e874a502ee.png">
<p>A user friendly confirmation message is shown that says the competition was created.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F5 - The cost to the creator of the competition will be (1 + starting reward value)</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146652953-d37a41a0-5bba-4d0e-ab89-51e296c9664b.png">
<p>The cost of creating a competition is 1 plus the starting reward value.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F5 - If they can’t afford it [cost], the competition should not be created</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146653080-d4eced9c-5ca6-4f18-a6bb-2de9e9bf5a10.png">
<p>If the user cannot afford it, an error message is shown, and the competition is not created.</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F5 -  If they can afford it [cost], automatically add them to the competition</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146653193-2316b9bd-bbb8-4339-8b66-ace7a3add763.png">
<p>The confirmation message is already shown above, and the creator is automatically added to the competition.</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<table>
<tr><td>F6 - Have a page where the User can see active competitions (not expired)  (2021-12-16)</td></tr>
<tr><td>Status: complete</td></tr>
<tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/active_competitions.php](https://gkp2-prod.herokuapp.com/Project/active_competitions.php)</p></td></tr>
<tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/95](https://github.com/GPintoNJIT/IT202--003-/pull/95)</p></td></tr>
<tr><td>
<table>
<tr><td>F6 - For this milestone limit the output to a maximum of 10</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146653573-7b362a7b-4a07-4b37-9c51-5fb0f819efe0.png">
<p>The competitions are limited to 10, via the LIMIT clause.</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F6 - Order the results by soonest to expire</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146653361-acefd597-bdcb-4e3c-8f11-ebc292fe6409.png">
<p>User can see active competitions, and they are ordered by soonest to expire.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<table>
<tr><td>F7 - User can join active competitions (2021-12-18)</td></tr>
<tr><td>Status: complete</td></tr>
<tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/active_competitions.php](https://gkp2-prod.herokuapp.com/Project/active_competitions.php)</p></td></tr>
<tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/96](https://github.com/GPintoNJIT/IT202--003-/pull/96)</p></td></tr>
<tr><td>
<table>
<tr><td>F7 - Creates an entry in CompetitionParticipants, recalculate the Competitions.participants value based on the count of participants for this competition from the CompetitionParticipants table, and update the Competitions.reward based on the # of participants and the appropriate math from the competition requirements above</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146654040-5149ab66-1ff1-4517-8717-f2d6507c5547.png">
<p>This function adds a user to a competition. It will create an entry in CompetitionParticipants, it updates the participants value of the competition in the Competitions table with the update_comp() function shown below, and it will also update the reward of the competition in the Competitions table with this function as well (shown below).</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F7 - The update_comp() function mentioned above.</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146629943-53ee28bc-46d9-4755-876c-922d499613f9.png">
<p>This function updates the competition as its parameter.</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F7 - Show proper error message if user is already registered</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146653926-9aded7bb-dce9-4897-9c49-134d361fa357.png">
<p>Proper error message is shown if the user is already registered for the competition they just clicked on.</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F7 - Show proper confirmation if user registered successfully</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146653784-cea8df3a-63f8-4844-ae5f-470f1cdd3dd8.png">
<p>Proper confirmation message is shown if the user is successfully registered for the competition that they just clicked on.</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<table>
<tr><td>F8 - Each new participant causes the Reward value to increase by at least 1 or 50% of the joining fee rounded up (2021-12-18)</td></tr>
<tr><td>Status: complete</td></tr>
<tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/active_competitions.php](https://gkp2-prod.herokuapp.com/Project/active_competitions.php)</p></td></tr>
<tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/96](https://github.com/GPintoNJIT/IT202--003-/pull/96)</p></td></tr>
<tr><td>
<table>
<tr><td>F8 - Each new participant causes the Reward value to increase by at least 1</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146629943-53ee28bc-46d9-4755-876c-922d499613f9.png">
<p>In lines 343 to 351, the current reward of the competition will be modified by adding 1 for every new person joining the competition. The update_comp method will be called inside another function called join_to_comp, which joins a user to a competition, and it will update that competition.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<table>
<tr><td>F9 - Create function that calculates competition winners  (2021-12-18)</td></tr>
<tr><td>Status: complete</td></tr>
<tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/login.php](https://gkp2-prod.herokuapp.com/Project/login.php)</p></td></tr>
<tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/97](https://github.com/GPintoNJIT/IT202--003-/pull/97)</p></td></tr>
<tr><td>
<table>
<tr><td>F9 - Get all expired and not paid_out competitions, for each competition - check that the participant count against the minimum required</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146654536-3c640c5f-f96c-4947-a1ab-7357c8eac497.png">
<p>All the comments in the code correspond to the requirements of the function; this snippet describes getting all expired and not paid out competitions, and for each competition, checking that the current participant count is greater than or equal to minimum participant count.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F9 - Get the top 3 winners; Option 1: Scores are calculated by the sum of the score from the Scores table where it was earned/created between Competition start and Competition expires timestamps</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146654604-e32a098c-da0f-4c50-b611-2fc9c72fd2f5.png">
<p>All the comments in the code correspond to the requirements of the function; this snippet describes getting the top 3 winners of each competition.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F9 - Calculate the payout (reward * place_percent), round up the value (it’s ok to pay out an extra point here and there), create entries for the Users in the PointsHistory table, apply the new values (SUM) to their points column in the Users table after entry is added, reason should be recorded as ‘competition’ (or something with more precise information), and mark the competition as paid_out = true</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/146654683-7cde6e23-17a5-4f4e-9e92-8307b0f1abaa.png">
<p>All the comments in the code correspond to the requirements of the function; this snippet describes calculating the payout of the top 3 winners of each competition, creating entries in the PointsHistory table for them, and marking each competition as paid out.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr></td></tr></table>
- Milestone 4

<table>
<tr><td>Milestone 4</td></tr><tr><td>
<table>
<tr><td>F1 - User can set their profile to be public or private (will need another column in Users table) (2021-12-21)</td></tr>
<tr><td>Status: complete</td></tr>
<tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/profile.php](https://gkp2-prod.herokuapp.com/Project/profile.php)</p></td></tr>
<tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/103](https://github.com/GPintoNJIT/IT202--003-/pull/103)</p></td></tr>
<tr><td>
<table>
<tr><td>F1 - If public, hide email address from other users</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/147157295-11157a55-8f25-46bc-8627-6d4e9a323a6e.png">
<p>The user can set their profile to be public or private. This user's username is test2.</p>
</td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/147157525-5f84e7ea-19d9-4159-a733-90b510f515af.png">
<p>test2's account status is set to true for is_public in the Users table after clicking the checkbox shown above.

</p>
</td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/147158899-7560c764-e963-4c5f-ab34-53d5b2135c29.jpg">
<p>The form will now show the email if $isMe is true, which is if the current form's user and the user of the profile page matches, and $edit is true, which is if the current user has the ability to edit the form, which is only true if $isMe is true and if the user wants to edit their profile instead of just viewing their profile. However, I included a link to their profile using the "<a>" and "href" components whenever a username is shown on a scoreboard, but it always linked back to the profile of the user currently logged in.</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<table>
<tr><td>F2 - User will be able to see their competition history (2021-12-22)</td></tr>
<tr><td>Status: incomplete</td></tr>
<tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/profile.php](https://gkp2-prod.herokuapp.com/Project/profile.php)</p></td></tr>
<tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/108](https://github.com/GPintoNJIT/IT202--003-/pull/108)</p></td></tr>
<tr><td>
<table>
<tr><td>F2 - Limit to 10 results</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/147159823-9f538f73-74bb-4a10-bc86-48071eedb2bd.png">
<p>The competitions that are displayed on the page are fetched from this method, and this snippet makes sure that the 10 most recently joined competitions for the user are shown.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F2 - If no results, show the appropriate message</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/147160106-2bf295a0-d011-4c78-979a-2260dae4dc11.png">
<p>If the user has not joined any competitions, an appropriate message is shown with a link to a page with all the active competitions, where they can join one.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F2 - Paginate anything after 10</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/ff0000/000000?text=incomplete"></td></tr>

<tr><td>
<img width="768px" src="">
<p>I could not figure out how to paginate the competition history, because I wanted to use a $_GET variable in the url to keep track of the page index for the competitions, but I already did that for the score history above, so I did not know how to use another variable in the url for $_GET.</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<table>
<tr><td>F3 - Add pagination to the Active Competitions view (2021-12-22)</td></tr>
<tr><td>Status: complete</td></tr>
<tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/active_competitions.php](https://gkp2-prod.herokuapp.com/Project/active_competitions.php)</p></td></tr>
<tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/105](https://github.com/GPintoNJIT/IT202--003-/pull/105)</p></td></tr>
<tr><td>
<table>
<tr><td>F3 - Show 10 competitions per page</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/147160959-430ba748-93e9-4d4e-93ac-dd22af53df1d.png">
<p>10 competitions are shown on each page.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F3 - Paginate anything after 10</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/147161054-9f8e0b1e-a0b0-45b6-811f-c80dfe7d77f8.png">
<p>There are actually 11 active competitions, so the leftover 1 is paginated to the next page.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F3 - If no results, show the appropriate message</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/147161198-50223494-e33a-4ee9-add6-3556ab786305.png">
<p>$comps_p is the variable that holds all the competitions to display on the current page. If the that variable is null, there are no competitions to display on the current page, and the message on the 2nd to last line is shown.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<table>
<tr><td>F4 - Anywhere a username is displayed should be a link to that user’s profile (2021-12-22)</td></tr>
<tr><td>Status: incomplete</td></tr>
<tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/view_comp.php?id=11](https://gkp2-prod.herokuapp.com/Project/view_comp.php?id=11)</p></td></tr>
<tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/103](https://github.com/GPintoNJIT/IT202--003-/pull/103)</p></td></tr>
<tr><td>
<table>
<tr><td>F4 - This includes all scoreboards</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/ff0000/000000?text=incomplete"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/147162169-024ac2ea-1257-4f11-9d59-fcafee559a20.png">
<p>This snippet is used in every scoreboard, and it sets the user ID and username to the user that set the score, but for some reason, the user ID is set to the user ID of the currently logged in user. I could not figure out how to fix it.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F4 - If the profile is private you can have the page just display “this profile is private” upon access</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/147162588-41314247-e6b4-4fda-95bf-c8ad55614f09.png">
<p>$public is a variable that is true if the account is public, and $isMe has been described above. If these are not true, then the user will be redirected to home and it will show a message saying that the user's profile is private.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<table>
<tr><td>F5 - Viewing an active or expired competition should show the top 10 scoreboard related to that competition (2021-12-22)</td></tr>
<tr><td>Status: complete</td></tr>
<tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/view_comp.php?id=4](https://gkp2-prod.herokuapp.com/Project/view_comp.php?id=4)</p></td></tr>
<tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/106](https://github.com/GPintoNJIT/IT202--003-/pull/106)</p></td></tr>
<tr><td>
<table>
<tr><td>F5 - Viewing an active or expired competition should show the top 10 scoreboard related to that competition</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/147162943-bfb3f51e-a869-43f4-8c01-4d00c3912e5f.png">
<p>After clicking on a link to a competition (whether in the competition history in profile on the active competitions page), information about the competition is shown along with the top 10 scores for that competition.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<table>
<tr><td>F6 - Game should be fully implemented/complete by this point (2021-12-21)</td></tr>
<tr><td>Status: complete</td></tr>
<tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/game.php](https://gkp2-prod.herokuapp.com/Project/game.php)</p></td></tr>
<tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/102](https://github.com/GPintoNJIT/IT202--003-/pull/102)</p></td></tr>
<tr><td>
<table>
<tr><td>F6 - Game should tell the player if they’re not logged in that their score will not be recorded.</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/147163417-6ae3d115-2436-4ce0-bef0-00b90d12b4c7.png">
<p>If the user is not logged in, the game will tell them that their score will not be saved.

</p>
</td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/147163368-1c7b7c3c-891f-4057-b8f4-9f5d08326da1.png">
<p>If the user is logged, the game will tell them that their score is saved.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<table>
<tr><td>F7 - User’s score history will include pagination (2021-12-22)</td></tr>
<tr><td>Status: complete</td></tr>
<tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/profile.php?s_start=2](https://gkp2-prod.herokuapp.com/Project/profile.php?s_start=2)</p></td></tr>
<tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/107](https://github.com/GPintoNJIT/IT202--003-/pull/107)</p></td></tr>
<tr><td>
<table>
<tr><td>F7 - Show latest 10 </td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/147163889-39fc702c-3aae-4411-b1a8-1967984a1029.png">
<p>The latest 10 scores are shown on the first page.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F7 - Paginate after 10</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/147163763-8dc9358a-0448-4ac6-aa2a-392e41e904cb.png">
<p>Anything after the 10 most recent scores are paginated.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F7 - Show appropriate message for no results</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/147163940-dea311e1-602f-4ecd-8ca8-da9f06d41d40.png">
<p>If the user has not set any scores, the game will suggest for them to set a score, with a link to the game.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<table>
<tr><td>F8 - Home page will have a weekly, monthly, and lifetime scoreboard (2021-12-21)</td></tr>
<tr><td>Status: complete</td></tr>
<tr><td>Links:<p>

 [https://gkp2-prod.herokuapp.com/Project/home.php](https://gkp2-prod.herokuapp.com/Project/home.php)</p></td></tr>
<tr><td>PRs:<p>

 [https://github.com/GPintoNJIT/IT202--003-/pull/104](https://github.com/GPintoNJIT/IT202--003-/pull/104)</p></td></tr>
<tr><td>
<table>
<tr><td>F8 - Will also have a link to the game</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/147164368-50261e09-74d3-4876-a464-94201bf9aad2.png">
<p>The home page has a link to the game, and the top 10 scores this week.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr>
<tr><td>
<table>
<tr><td>F8 - Scoreboards will show username and points for the session</td></tr>
<tr><td>Status: 
<img width="100" height="20" src="https://via.placeholder.com/400x120/009955/fff?text=completed"></td></tr>

<tr><td>
<img width="768px" src="https://user-images.githubusercontent.com/89927115/147164496-eb218e84-f854-4140-9222-8046be982824.png">
<p>The home page has the top 10 scores this month, and the top 10 scores in the lifetime of the game. Also, just like the image above, the scores have the username and the score for that session.

</p>
</td></tr>

</td>
</tr>
</table>
</td>
</tr></td></tr></table>
### Intructions
#### Don't delete this
1. Pick one project type
2. Create a proposal.md file in the root of your project directory of your GitHub repository
3. Copy the contents of the Google Doc into this readme file
4. Convert the list items to markdown checkboxes (apply any other markdown for organizational purposes)
5. Create a new Project Board on GitHub
   - Choose the Automated Kanban Board Template
   - For each major line item (or sub line item if applicable) create a GitHub issue
   - The title should be the line item text
   - The first comment should be the acceptance criteria (i.e., what you need to accomplish for it to be "complete")
   - Leave these in "to do" status until you start working on them
   - Assign each issue to your Project Board (the right-side panel)
   - Assign each issue to yourself (the right-side panel)
6. As you work
  1. As you work on features, create separate branches for the code in the style of Feature-ShortDescription (using the Milestone branch as the source)
  2. Add, commit, push the related file changes to this branch
  3. Add evidence to the PR (Feat to Milestone) conversation view comments showing the feature being implemented
     - Screenshot(s) of the site view (make sure they clearly show the feature)
     - Screenshot of the database data if applicable
     - Describe each screenshot to specify exactly what's being shown
     - A code snippet screenshot or reference via GitHub markdown may be used as an alternative for evidence that can't be captured on the screen
  4. Update the checklist of the proposal.md file for each feature this is completing (ideally should be 1 branch/pull request per feature, but some cases may have multiple)
    - Basically add an x to the checkbox markdown along with a date after
      - (i.e.,   - [x] (mm/dd/yy) ....) See Template above
    - Add the pull request link as a new indented line for each line item being completed
    - Attach any related issue items on the right-side panel
  5. Merge the Feature Branch into your Milestone branch (this should close the pull request and the attached issues)
    - Merge the Milestone branch into dev, then dev into prod as needed
    - Last two steps are mostly for getting it to prod for delivery of the assignment 
  7. If the attached issues don't close wait until the next step
  8. Merge the updated dev branch into your production branch via a pull request
  9. Close any related issues that didn't auto close
    - You can edit the dropdown on the issue or drag/drop it to the proper column on the project board