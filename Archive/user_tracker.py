from flask import Flask, render_template, request, jsonify
from datetime import datetime
from pymongo import MongoClient

# MongoDB connection details (replace with yours)
#mongo_uri = "mongodb://barton_admin:bartonl411@localhost:27017/"
mongo_uri = "mongodb+srv://barton:11KYG7oKKP2USHFa@cluster0.hwvadag.mongodb.net/";
mongo_db_name = "user_tracking"
mongo_collection_name = "user_data"

# Connect to MongoDB
client = MongoClient(mongo_uri)
db = client[mongo_db_name]
collection = db[mongo_collection_name]

app = Flask(__name__)

@app.route("/")
def home():
  # Get user data from MongoDB
  user_data = list(collection.find())
  
  return render_template("index.html", user_data=user_data)

# Existing data collection logic (assuming it's in a variable called 'collection')

@app.route("/get_document_count")
def get_document_count():
    # Get the count of documents in the collection
    document_count = collection.count_documents({})

    print("get_docuement_count=",document_count);
    
    # Return the count as JSON
    return jsonify({"count": document_count})
  
@app.route("/track_user", methods=['POST'])
def track_user():
  # Get client information
  ip = request.remote_addr  # Client's IP address
  page = request.url  # Full URL of the requested page
  user_agent = request.headers.get('User-Agent')  # Client's user agent string
  host = request.host

  time = datetime.now()
  print("track_user, time: ", time, ", host: ", host);

  # Create a document for user data
  user_document = {
      "host": host,
      "ip": ip,
      "page": page,
      "user_agent": user_agent,
      "time": time
  }

  # Insert the document into MongoDB
  collection.insert_one(user_document)

  print("User data tracked successfully!")
  return "User data tracked successfully!"

if __name__ == "__main__":
  app.run(debug=True, ssl_context='adhoc')
