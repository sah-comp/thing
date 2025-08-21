# Thing

Thing is the last PHP toolbox I want to plan, test, develop and deploy in my life.

## Preface

It's months since I found enough energy to jump into _Things_ again. At least I haven't been on the keyboard for a long time, but all that thinking about wether to build this on [Symfony](https://symfony.com) or [Laravel](https://laravel.com) and reading posts about how to do things and all that code reviews, book reading and so on lead me back to the idea that, in the end, I am still the one to incorporate the business model. Isn't that done through documentation, or story coding more than by the raw program code itself? Are these not prompts? Let's see.

Personally I qualify as a cowboy coder anyway.

When using these framework as a foundation will I be using them the (right) way, so my customers will benefit from it because my source code is a open book to any other developer?
I don't think so, but I know nothing about the stuff I'm doing. Quite like the large language models our days I once read a lot and I have a feeling for words flowing out of my mouth. You, poor reader might object, because you already had a hard time reading up to this point.

## What is Thing?

What is _Thing_? It starts as a decision tree with some twists.

### First start

_Thing_ will start with this:

Nothing.

When you install _Thing_ the database is empty. Thus, when you navigate to your installation on the web you will be presented with an empty[^1] screen.

Activate the back of the empty page.
How to do that? You'll see it when you use it.

If you create the first node of _Thing_ it will always be a _creator_-node. A creator is an individual entity (doppelmoppel?) that will be able to add nodes, add decisions, add users, delete stuff and so on. Think of a creator as a master of all things happening in his realm.
Therefor a creator has some mandatory attributes that have to be verified and here they are:

* email
* password

For each node there will also be meta informations. The most basic are (to do: normalize please):

* date of creation
* created by
* date of last change
* edited by
* deleted
* deleted on
* deleted by
* type
* version

Based on one or more criterias there may be a lot of other attributes or information that can be attached to a node. For example a node is a citation of a famous but fictional character. In that case you may add information stating who said it, where it was said, when it was said and why. These extra attribute sets will most likely depend on the _type_ od a _node_.

You can now add node after node. Phrase your node as a question and add optional yes or no records (nodes). If there are no left (yes) or right (no) links the node is a leaf.

### Example

parent: NULL
id: 100
payload: Do we already work together?
left (yes): 101
right (no): 102

parent: NULL
id: 101
payload: Do you need help quickly?
left (yes): 103
right (no): NULL

parent: NULL
id: 102
payload: I build solutions. Let me help, call 777-LUCKY
left (yes): NULL
right (no): NULL

parent: NULL
id: 103
payload: Send mail or call 666-HELPME?
left (yes): 103
right (no): NULL

Make id 100 the entry point to your (website) and _Thing_ will make it easy for your visitors to make the decision to call you and you'll have a nice conversation by mail or telephone.

Realms can pair. Pairs may spawn. Each creator, creature has a shadow, but the node may not be aware of his shadow.

Realms pair through a central service point, a _place_.

Currently I have a vague idea of the look and feel when I use _Thing_ as a dataset for my upcoming website but no vision about the adminstrational backend. Maybe leave it to a graphics designer, try it myself, only do a cli version for editing the datasets?

Let's hit the break.

[^1]: In some countries there will be a link to legal stuff on that empty page.
