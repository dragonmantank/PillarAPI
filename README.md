# PillarAPI

Last Updated: 2010-12-24, Chris Tankersley

PillarAPI is a lightweight REST server written in PHP. It uses standard HTTP
verbs to route to and interact with different resources. Resources are standard
PHP objects with some special functions.

## Resources and URLs

Resources are a 'thing' that is on the server. This could be users, categories,
messages, anything. You interact with these resources in two ways: with verbs
and with queries.

Unlike some REST-like servers, PillarAPI relies on the HTTP verbs to determine
what the client wants. The client must support GET, POST, PUT, and DELETE verbs
to take full advantage of PillarAPI.

### URLs

PillarAPI uses URLs to find resources, and queries to narrow down searches (if
the resource supports queries). Here are some sample URLs:

*Create New User*

PUT http://mydomain.com/users

*Find Users with First Name Bob*

GET http://mydomain.com/users?firstname=Bob

*Delete User 34*

DELETE http://mydomain.com/users?id=34

### Verbs

PillarAPI takes advantage of the built in HTTP verbs for working with resources.
A client may request a list of

#### GET

GET requests will return a single or list of objects. Usually a query string
will help narrow down the results.

#### POST

Updates a resource with the POSTed information. This is in contrast to PUT which...

#### PUT

Creates a new resource.

#### DELETE

Removes a resource. Normally needs a query string to help define which exact
set to delete.

## Resource Objects

Resources in PillarAPI are just PHP objects that extend `Pillar_Rest_Resource`.
The Resource object needs to have a public action for each type of verb that
the resource can handle.

The Resource takes the passed request and formulates a Pillar_Rest_Response
object. The Response object contains an HTTP code and a textual response. The
response can be either HTML or JSON.

    class Myresource extends Pillar_Rest_Resource {
        public function get() {
            $query = $this->getRequest()->getQuery();
            // Do some stuff and save it to $output
            $this->response->setBody($output);
            $this->response->setContentType(Pillar_Rest_Response::CONTENT_JSON);
        }

        public function put() {
            $data = $this->getRequest()->getRequestVars();
            // Try and create resource, and check if created
            $response = new Pillar_Rest_Response();
            if($created) {
                $this->response->setCode(201);
                $this->response->setBody(json_encode(array('id'=>$id)));
                $this->response->setContentType(Pillar_Rest_Response::CONTENT_JSON);
            } else {
                $this->response->setCode(500);
                $this->response->setBody('Unable to create: ".$error);
            }
        }
    }

## Server

PillarAPI comes with a Server object, and an implementation of the server in
`public/index.php`. The Server takes a request and transforms it into a response.
In the supplied implementation, the response it checked for exceptions and,
depending on if there were exceptions and the type, renders a response.

The basic usage for the server is:

    // Tell PillarAPI where your resources are located
    Pillar_Rest_Resource_Manager::addNamespace('My_Resource', 'My/Resource');
    $server = new Pillar_Rest_Server(new Pillar_Rest_Request());
    $server->dispatch();
