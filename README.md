## The idea:
We wanted to create a way to connect students who were looking for a place to study, or were open for a study partner, in a simple and intuitive manner.

## What it does
After signing up, a user can create a 'Study Session' using a geographic location fetched from the map.  They can add details such as the subject their studying, their skills, and their desired level of interaction using an easy to recognize 'Traffic Light' system.  A red light indicates that the user is looking to _lock in_ and is looking for minimal distractions and accountability.  A yellow light indicates the user is looking for a 'Chat and work' session, a healthy mix of chat, but primarily focused on their work, and a green light indicates a 'Yap' session where studying is less engaging but they still need to get work done.

## How we built it
We designed the pages in Figma, and use a SQLite backend for our user accounts and active study sessions.  Our map is built in Javascript, using the Open Street Maps API. We had team members focused on back-end implementation, and some focused on front-end usability.

## Challenges we ran into
As we proceeded, the scope of our project grew and the difficulty in implementation increased several fold.  We're using as few pre-built frameworks as possible.  FriendLink pages are hand-crafted with love and our account/active session engine and interactive map were built from the ground up using SQLite, PHP, and Javascript.  

## Accomplishments that we're proud of
Our interactive map is our stand-out feature.  It pulls data points from our SQLite database, maps them, clusters close points, and includes a list that highlights the selected session!

## What we learned
We spent a lot of time developing a cohesive idea, built around a strong desire for a tool we would use.  We initially tried to add our dream list of features and spent a lot of time designing things that weren't available for our demo. Our team had minimal Web Development experience going in, and managed to learn PHP Session with user details, and effective integration with Javascript using custom API's.

## What's next for FriendLink
We're excited to show off our demo, and if other students like our platform we'd love to expand it into an open, adoptable tool for students around the world!
