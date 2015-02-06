# HXCMusic
**If you are from Mobiquity:**
 > Please keep checking back, as for the next couple of days I will be documenting, optimizing code, and removing redudent code, adding new repos from other projects ect... As a coder who loves development, I oddly hate code, and constantly try to optimize existing code, and new code to accoplish my goal in the least amount of lines. You will also see I am somewhat obsessed with automating any and all processes that I can, like my auto cache, notifications, mysql backups, automated mover.io backups, and others. Any and all functions I find myself using a lot I add it to my App.Util object like string formatting/stripping, capitalization, grabbing file names, checking if an element is in viewport/view ect..

### Made with
[BackboneJS](https://github.com/jashkenas/backbone/) + [MarionetteJS](https://github.com/marionettejs/backbone.marionette)

### About me
```
function Jimmy(life){
 var passion;
 for (var dreams=0;dreams<life.length;dreams++){
  passion = $('.Jimmy-Rousseau[data-id="'+life[dreams].hopes+'"]');
  if(passion.length > 0){
   passion.append(life[dreams].content);
 }else{
  alert('FAIL');
  break;
 }
}
return dreams.length >= life.length ? 'success' : 'failure';
}
Jimmy(life);
```

### About this project
This was my passion, and pet project for 6+ years (was actually self-employed for this website for most of those years) till recently when I could no longer afford server costs to keep the website online.. because this was a big part of my portfolio, I decided to put it on Github, to show my skills, and passion for my work to Mobiquity, and any other potential employers. *wink wink*

This also served as my personal library of previous code I written, there is a lot of files that are no longer in use, but kept them around as a reference for future problems I may run into.

### Here you will find
- Custom restFUL API
  - from PHP using Mysql database as well as unfinished node.js code converting my api to js from php.
Node.js app for rendering my one page javascript app for search engine crawlers (as they cannot execute JS)

- Node.js app for processing my SASS files into CSS

- Custom cache system, as well as a custom compiler for my JS assets to become a single minified js file.
 - This system compiles the code to a single file but also checks modified date of my js assets and re-compiles if needed (on-demand)

- Custom debugging & development code
 - I made for myself as well as a production publishing process.
when in debug mode, you have to have a proper session (php) and adds asset js files individually & unminified for debugging lines of code, instead of compiling into one file, and minifying for production session.

- Use of socket.io (websockets) for live updates and notifications of other songs being listened to.

- Custom error logging

and more...


