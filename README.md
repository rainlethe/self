This article was translated by Google translator written in Korean.  
Therefore, please note that vocabulary can be awkward.  

# 1. Self introduction Library  
Self libraries have made in order to keep the consistency and ease of development to the php development.  
The name was derived from the self Self keywords pointing to himself in python.  
  
# 2. Operating environment  
Work in PHP 5.2 and later.  
If possible, we recommend the PHP 5.3 or higher.  
  
# 3. Why did you made?  
I have made I wanted to write a little easier.  
    
Of course, in the same framework level Codeigniter or Laravel offer many helpers.  
But without the framework Plain Old Php Page also needed a library that can be used.  
So self is a library, not a framework.

** Even the old PHP pages, helpers or libraries or even framework Please attach used anywhere. **
  
# 4. The structure of each file  
The structure of self is shown below.  
  
/self.php --- the core of the self.  
/selfconfig.php --- a file that defines library for use in self.  
/ Various libraries are used in selflib / --- self located.  
    
3. How do I use it?  
You must first include the self.  
  
```
<? php
include_once ('self.php');
```

If you want to count the length of the string
```
mb_strlen ("string", 'UTF-8')
```
instead
```
$ self-> fromString ("string") -> length ();
```
If you are ever called.