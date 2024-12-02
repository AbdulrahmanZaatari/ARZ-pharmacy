# ARZ Pharmacy - Personalized Medicine & Sales Management System

ARZ Pharmacy is a web-based platform designed to provide personalized medical care and enhance pharmacy operations for the Lebanese community. The name "ARZ," meaning "Cedar" in Arabic, symbolizes Lebanon's heritage and reflects our commitment to serving the people of Lebanon with high-quality and personalized pharmaceutical services. The application combines modern technology with role-based functionalities, allowing users, pharmacists, and pharmacy owners to interact efficiently and securely.

---

## Features

### Personalized Medicine
- **Electronic Health Records (EHR)**: Each registered user fills out a detailed questionnaire that generates their personal EHR, helping pharmacists provide tailored treatments.
- **Medication Recommendations**: EHR-based treatment recommendations ensure personalized and effective care.

### Q&A Platform
- **Public Q&A Section**: Users can ask health-related questions anonymously, and only verified pharmacists can provide expert answers.

### Role-Based Access
- **Users**: Manage their profiles, view EHRs, place orders, and track purchases.
- **Pharmacists**: Access EHRs, review orders, and respond to public queries.
- **Owners**: Monitor pharmacy performance, track sales, and manage staff and operations.

### Sales Management
- **Order Management**: Add, update, and delete orders.
- **Sales Tracking**: Track daily sales with detailed summaries and insights.
- **PDF Reports**: Generate daily sales reports, including top products sold and order details.
- **Dashboard**: Role-based dashboards for users, pharmacists, and owners to track personalized tasks and metrics.

---

## Requirements

1. XAMPP or similar web server running PHP and MySQL.
2. A browser for accessing the application.
3. MySQL database configured with the required schema.

---

## Setup Instructions (Running the Code on XAMPP)

1. **Install XAMPP**:
   - Download and install [XAMPP](https://www.apachefriends.org/index.html).

2. **Clone the Repository**:
   - Place the project folder in the `htdocs` directory of your XAMPP installation (usually located at `C:\xampp\htdocs`).

3. **Import the Database**:
   - Open phpMyAdmin (`http://localhost/phpmyadmin`).
   - Create a new database (e.g., `arz_pharmacy`).
   - Import the `.sql` file included in the repository to set up the required tables and data.

4. **Configure the Database Connection**:
   - Open the `connection.php` file in the project directory.
   - Update the database credentials:
     ```php
     $servername = "localhost";
     $username = "root"; // Default XAMPP user
     $password = ""; // Default XAMPP password (leave blank)
     $dbname = "arz_pharmacy"; // Database name
     ```

5. **Start XAMPP**:
   - Open the XAMPP Control Panel.
   - Start the **Apache** and **MySQL** modules.

6. **Access the Application**:
   - Open a browser and navigate to `http://localhost/<project-folder>`.

7. **Run the Application**:
   - Register as a user, fill out the questionnaire, and generate your EHR.
   - Explore the features based on your role (User, Pharmacist, or Owner).

---

## Contact

For issues, suggestions, or contributions, please feel free to open an issue on the repository or contact the maintainer. Together, we can make ARZ Pharmacy even better for the Lebanese community.