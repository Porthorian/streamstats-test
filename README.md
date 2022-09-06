# streamstats-test

Streamlabs Assignment

Thank you for choosing to invest your time in this assignment.  We recognize it’s difficult to find the time to complete a coding assignment, and we value your time and investment in this process with us.

Stack
The technology you use is up to you, at Streamlabs we mainly make use of PHP, Laravel, Vue, React, TypeScript, MySQL. Please do not use NodeJS to complete the project.

Application: Top StreamStats App

You will build an application called “StreamStats”. This application is aimed at helping Twitch viewers get a quick look at how the channels they watch compare to the top 1000 live streams. They will log into your application with a Twitch account via an OAuth flow that you will implement. 

A user has the following pieces of information that have to be stored:
Twitch Id
Email
Username

The stats will show a comparison between the streams they are watching and the top 1000 current live streams. For the purpose of this assignment, you will seed the database with data from the Twitch (Helix) API about top live streams. Shuffle this data before inserting.

A stream has several pieces of information that have to be stored:
Channel name
Stream title
Game name
Number of viewers
When it started

Once a user is logged in display the following information to the user:
Total number of streams for each game
Top games by viewer count for each game
Median number of viewers for all streams
List of top 100 streams by viewer count that can be sorted asc & desc
Total number of streams by their start time (rounded to the nearest hour)
Which of the top 1000 streams is the logged in user following?
How many viewers does the lowest viewer count stream that the logged in user is following need to gain in order to make it into the top 1000?
Which tags are shared between the user followed streams and the top 1000 streams? Also make sure to translate the tags to their respective name?

For the above statistics calculate aggregate two of the above tasks through the application layer (aggregate using arrays instead of queries) and the remainder using db queries

When it comes to authentication make sure to have the user’s logged in session expire after a 1 hour period so they are forced to login again.

For the final portion of the assignment setup a cronjob that refreshed the streams data every 15 minutes.

UX
Build a simple SPA (Single Page Application) using Javascript & CSS to visualize the data.

Deliverables
This is a take-home assignment and you should not spend more than 6 hours on the project. 
The code is to be published on a public github repository for our team to access. Make sure that we can see your progress in your commit history, a single commit is not enough.

In order to demo the application you can either submit a short video showing us the entire flow and the results or you can host it anywhere on the web for us to access it.
