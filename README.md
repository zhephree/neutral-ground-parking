# Can I Park on the Neutral Ground
## Why?
A common occurrence in New Orleans is flooding. So common that the city will occasionally allow people to park their cars on higher ground, which for us is the neutral ground (median to everyone else). With an extra 2 feet of rainfall so far this year, this happens more often than normal. The city-run Twitter account @NOLAReady will tweet (and send SMS notifications) when we're allowed to do so, and when it's time to move your cars because they're going to start ticketing us as punishment for trying to avoid the failures of government and industry.

## How?
So, to make it easy to see when we can park on the neutral ground, I made [caniparkontheneutralground.com](caniparkontheneutralground.com) that simply tells you "YES" or "NO". It determines this by getting tweets from @NOLAReady via a Zapier trigger that does a GET request to `webhook.php` with the tweet text passed in as a URL parameter named `tweet`. The script then looks at the text of the tweet and if it's announcing that neutral ground parking is allowed, it stores the current time as the start time and if the tweet mentions when parking will be disallowed again, it parses that out and stores it as the end time. If there is no end time, it stores 9999999999 as the end time as a lazy way to ensure the end time value is in the future. If the tweet mentions only when restrictions will go back in place, we set the start time to the current time and the end time to whatever the tweet says is the end time.

When a user visits the website, it looks at the current time and sees if it is between the start time and the end time. If so, prints "YES" and displays some water and if not, prints "NO". Very impressive stuff.

The start and end times are stores in `dates.json` because a database would have been absolute over-engineering, which honestly would've been a little funny.

The parsing etc is very basic `strpos` and `substr` stuff. *Why didn't you do regex????* I dunno have you ever heard of "doing whatever the fuck you want"?

It also uses my favorite PHP function `strtotime` to convert stuff like "8am Tuesday" into a useful Unix timestamp.

## Installing

1. Download the files

## Building

1. Done.

Ok I think that's it. Oh, I made the grass background image using the "grass" shape in Photoshop in random spots and then tiling it. The wave is an SVG I generated on [this website](https://getwaves.io) that miraculously existed.

The code is not optimized or "cool" or using a "modern" "language" or "acceptable" "best practices" because who gives a shit

Follow me on twitter at [@animatedGeoff](https://twitter.com/animatedgeoff) or go to [my website](http://geoffreygauchet.com) if you wanna see other dumb shit i do.