-- CS306 Project Phase 3 SQL Dump
-- Group Members: Efe Çalışkaner, Doruk Kocaman, Emre Yontucu, Halis Cem Şahin

SET SQL_SAFE_UPDATES = 0;
DROP DATABASE IF EXISTS cs306_project;
CREATE DATABASE cs306_project;
USE cs306_project;

-- 1. OWNER TABLE
CREATE TABLE Owner (
    Owner_ID INT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Email VARCHAR(100),
    Phone VARCHAR(20)
);

INSERT INTO Owner VALUES
(1, 'John Smith', 'johnsmith@gmail.com', '555-1001'),
(2, 'Emma Johnson', 'emma.johnson@yahoo.com', '555-1002'),
(3, 'Ali Demir', 'ali.demir@gmail.com', '555-1003'),
(4, 'Laura Kim', 'laura.kim@hotmail.com', '555-1004'),
(5, 'Carlos Lopez', 'carlos.lopez@gmail.com', '555-1005'),
(6, 'Mia Chen', 'mia.chen@gmail.com', '555-1006'),
(7, 'David Brown', 'david.brown@yahoo.com', '555-1007'),
(8, 'Sophia Miller', 'sophia.miller@gmail.com', '555-1008'),
(9, 'James Wilson', 'james.wilson@gmail.com', '555-1009'),
(10, 'Elif Kaya', 'elif.kaya@gmail.com', '555-1010');

-- 2. ADDRESS TABLE
CREATE TABLE Address (
    Address_ID INT PRIMARY KEY,
    City VARCHAR(100),
    Postal_Code VARCHAR(20)
);

INSERT INTO Address VALUES
(1, 'Istanbul', '34000'),
(2, 'Ankara', '06000'),
(3, 'Izmir', '35000'),
(4, 'Bursa', '16000'),
(5, 'Antalya', '07000'),
(6, 'London', 'SW1A1AA'),
(7, 'New York', '10001'),
(8, 'Berlin', '10115'),
(9, 'Paris', '75000'),
(10, 'Rome', '00100');

-- 3. PROPERTY TABLE
CREATE TABLE Property (
    Property_ID INT PRIMARY KEY,
    Type VARCHAR(50),
    Size INT,
    Status VARCHAR(50),
    Price DECIMAL(15, 2),
    Owner_ID INT NOT NULL,
    Address_ID INT,
    FOREIGN KEY (Owner_ID) REFERENCES Owner(Owner_ID)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    FOREIGN KEY (Address_ID) REFERENCES Address(Address_ID)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

INSERT INTO Property VALUES
(1, 'Apartment', 120, 'Available', 450000, 1, 1),
(2, 'Villa', 300, 'Sold', 1250000, 5, 8),
(3, 'Office', 200, 'Available', 800000, 9, 3),
(4, 'Apartment', 90, 'Available', 350000, 7, 6),
(5, 'Villa', 250, 'Available', 990000, 3, 10),
(6, 'Studio', 60, 'Rented', 200000, 10, 4),
(7, 'Apartment', 130, 'Available', 500000, 2, 9),
(8, 'Office', 400, 'Sold', 1750000, 6, 2),
(9, 'Apartment', 80, 'Available', 300000, 4, 7),
(10, 'Villa', 320, 'Rented', 1150000, 8, 5);

-- 4. OFFER TABLE
CREATE TABLE Offer (
    Offer_ID INT PRIMARY KEY,
    Offer_Price DECIMAL(15, 2),
    Offer_Date DATE,
    Status VARCHAR(50),
    Property_ID INT NOT NULL,
    FOREIGN KEY (Property_ID) REFERENCES Property(Property_ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

INSERT INTO Offer VALUES
(1, 440000, '2025-01-10', 'Pending', 1),
(2, 1260000, '2025-01-15', 'Accepted', 7),
(3, 780000, '2025-01-20', 'Rejected', 4),
(4, 340000, '2025-01-22', 'Pending', 9),
(5, 950000, '2025-02-01', 'Accepted', 3),
(6, 210000, '2025-02-05', 'Accepted', 10),
(7, 510000, '2025-02-10', 'Rejected', 6),
(8, 1780000, '2025-02-12', 'Accepted', 2),
(9, 290000, '2025-02-15', 'Pending', 5),
(10, 1180000, '2025-02-18', 'Pending', 8);

-- 5. AGENT TABLE
CREATE TABLE Agent (
    Agent_ID INT PRIMARY KEY,
    Phone VARCHAR(20),
    Email VARCHAR(100),
    Agency_Name VARCHAR(100)
);

INSERT INTO Agent VALUES
(1, '555-2001', 'agent.ayse@gmail.com', 'DreamHomes'),
(2, '555-2002', 'agent.mehmet@gmail.com', 'Skyline Realty'),
(3, '555-2003', 'agent.lisa@yahoo.com', 'UrbanNest'),
(4, '555-2004', 'agent.tom@gmail.com', 'DreamHomes'),
(5, '555-2005', 'agent.zeynep@gmail.com', 'PrimeEstates'),
(6, '555-2006', 'agent.mark@gmail.com', 'UrbanNest'),
(7, '555-2007', 'agent.jane@gmail.com', 'Skyline Realty'),
(8, '555-2008', 'agent.ahmet@gmail.com', 'PrimeEstates'),
(9, '555-2009', 'agent.sara@gmail.com', 'DreamHomes'),
(10, '555-2010', 'agent.murat@gmail.com', 'UrbanNest');

-- 6. LISTING TABLE
CREATE TABLE Listing (
    Listing_ID INT PRIMARY KEY,
    Listing_Date DATE,
    Status VARCHAR(50),
    Property_ID INT NOT NULL,
    Agent_ID INT NOT NULL,
    FOREIGN KEY (Property_ID) REFERENCES Property(Property_ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (Agent_ID) REFERENCES Agent(Agent_ID)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

INSERT INTO Listing VALUES
(1, '2025-01-05', 'Active', 1, 1),
(2, '2025-01-10', 'Closed', 5, 3),
(3, '2025-01-15', 'Active', 8, 6),
(4, '2025-01-20', 'Active', 3, 9),
(5, '2025-01-25', 'Active', 10, 2),
(6, '2025-01-28', 'Closed', 2, 7),
(7, '2025-02-01', 'Active', 6, 4),
(8, '2025-02-05', 'Closed', 9, 10),
(9, '2025-02-10', 'Active', 4, 8),
(10, '2025-02-15', 'Active', 7, 5);

-- 7. CUSTOMER TABLE
CREATE TABLE Customer (
    Customer_ID INT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Phone VARCHAR(20),
    Email VARCHAR(100)
);

INSERT INTO Customer VALUES
(1, 'Michael Green', '555-3001', 'michael.green@gmail.com'),
(2, 'Anna White', '555-3002', 'anna.white@yahoo.com'),
(3, 'Eren Yılmaz', '555-3003', 'eren.yilmaz@gmail.com'),
(4, 'Linda Black', '555-3004', 'linda.black@gmail.com'),
(5, 'Can Demir', '555-3005', 'can.demir@hotmail.com'),
(6, 'Olivia Brown', '555-3006', 'olivia.brown@gmail.com'),
(7, 'Jack Lee', '555-3007', 'jack.lee@yahoo.com'),
(8, 'Fatma Kaya', '555-3008', 'fatma.kaya@gmail.com'),
(9, 'Chris Evans', '555-3009', 'chris.evans@gmail.com'),
(10, 'Selin Arslan', '555-3010', 'selin.arslan@gmail.com');

-- 8. APPOINTMENT TABLE
CREATE TABLE Appointment (
    Appointment_ID INT PRIMARY KEY,
    Appointment_Date DATE,
    Appointment_Time TIME,
    Customer_ID INT NOT NULL,
    Agent_ID INT NOT NULL,
    FOREIGN KEY (Customer_ID) REFERENCES Customer(Customer_ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (Agent_ID) REFERENCES Agent(Agent_ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

INSERT INTO Appointment VALUES
(1, '2025-02-01', '10:00:00', 1, 1),
(2, '2025-02-02', '11:00:00', 6, 9),
(3, '2025-02-03', '13:30:00', 9, 4),
(4, '2025-02-04', '15:00:00', 3, 7),
(5, '2025-02-05', '09:30:00', 10, 2),
(6, '2025-02-06', '10:15:00', 4, 8),
(7, '2025-02-07', '14:45:00', 7, 5),
(8, '2025-02-08', '16:00:00', 2, 10),
(9, '2025-02-09', '12:00:00', 5, 3),
(10, '2025-02-10', '11:45:00', 8, 6);

-- 9. PAYMENT TABLE
CREATE TABLE Payment (
    Payment_ID INT PRIMARY KEY,
    Payment_Date DATE,
    Payment_Method VARCHAR(50),
    Amount DECIMAL(15, 2),
    Owner_ID INT NOT NULL,
    FOREIGN KEY (Owner_ID) REFERENCES Owner(Owner_ID)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

INSERT INTO Payment VALUES
(1, '2025-02-11', 'Credit Card', 1250000, 2),
(2, '2025-02-12', 'Cash', 1750000, 8),
(3, '2025-02-13', 'Wire Transfer', 950000, 5),
(4, '2025-02-14', 'Credit Card', 210000, 6),
(5, '2025-02-15', 'Cash', 1180000, 10),
(6, '2025-02-16', 'Wire Transfer', 780000, 3),
(7, '2025-02-17', 'Credit Card', 500000, 7),
(8, '2025-02-18', 'Cash', 340000, 4),
(9, '2025-02-19', 'Wire Transfer', 440000, 1),
(10, '2025-02-20', 'Credit Card', 300000, 9);

-- 10. REVIEW TABLE
CREATE TABLE Review (
    Review_ID INT PRIMARY KEY,
    Rating INT CHECK (Rating >= 1 AND Rating <= 5),
    Comment_Text TEXT,
    Review_Date DATE,
    Customer_ID INT NOT NULL,
    Property_ID INT NOT NULL,
    FOREIGN KEY (Customer_ID) REFERENCES Customer(Customer_ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (Property_ID) REFERENCES Property(Property_ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

INSERT INTO Review VALUES
(1, 5, 'Very professional agent!', '2025-02-12', 1, 1),
(2, 4, 'Smooth transaction overall.', '2025-02-13', 8, 5),
(3, 3, 'Communication could be better.', '2025-02-14', 4, 9),
(4, 5, 'Excellent service.', '2025-02-15', 10, 3),
(5, 4, 'Friendly and helpful.', '2025-02-16', 6, 7),
(6, 5, 'Fast response, great work.', '2025-02-17', 3, 10),
(7, 2, 'Late for appointment.', '2025-02-18', 9, 2),
(8, 4, 'Good experience.', '2025-02-19', 2, 6),
(9, 5, 'Loved the process.', '2025-02-20', 7, 4),
(10, 3, 'Average experience.', '2025-02-21', 5, 8);



-- TRIGGERS AND STORED PROCEDURES

-- Trigger 1: trg_increment_appointment_count

ALTER TABLE Agent ADD COLUMN appointment_count INT DEFAULT 0;


UPDATE Agent a SET appointment_count = (
    SELECT COUNT(*) FROM Appointment ap WHERE ap.Agent_ID = a.Agent_ID
);

DELIMITER //
CREATE TRIGGER trg_increment_appointment_count
AFTER INSERT ON Appointment
FOR EACH ROW
BEGIN
    UPDATE Agent
    SET appointment_count = appointment_count + 1
    WHERE Agent_ID = NEW.Agent_ID;
END //
DELIMITER ;

-- Trigger 2: trg_validate_payment_amount
DELIMITER //
CREATE TRIGGER trg_validate_payment_amount
BEFORE INSERT ON Payment
FOR EACH ROW
BEGIN
    IF NEW.Amount < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Payment amount cannot be negative';
    END IF;
    IF NEW.Amount = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Payment amount must be greater than zero';
    END IF;
END //
DELIMITER ;

-- Trigger 3: after_offer_accepted
DELIMITER //
CREATE TRIGGER after_offer_accepted
AFTER UPDATE ON Offer
FOR EACH ROW
BEGIN
    IF NEW.Status = 'Accepted' AND OLD.Status != 'Accepted' THEN
        UPDATE Property
        SET Status = 'Sold'
        WHERE Property_ID = NEW.Property_ID;
    END IF;
END //
DELIMITER ;

-- Trigger 4: trg_prevent_duplicate_appointments
DELIMITER //
CREATE TRIGGER trg_prevent_duplicate_appointments
BEFORE INSERT ON Appointment
FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1
        FROM Appointment
        WHERE Agent_ID = NEW.Agent_ID
          AND Appointment_Date = NEW.Appointment_Date
          AND Appointment_Time = NEW.Appointment_Time
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'This agent is already booked for this specific date and time.';
    END IF;
END //
DELIMITER ;

-- Stored Procedure 1: sp_search_available_properties
DELIMITER //
CREATE PROCEDURE sp_search_available_properties(
    IN p_property_type VARCHAR(50),
    IN p_min_price DECIMAL(15,2),
    IN p_max_price DECIMAL(15,2),
    IN p_city VARCHAR(100)
)
BEGIN
    SELECT
        p.Property_ID,
        p.Type,
        p.Size,
        p.Price,
        p.Status,
        a.City,
        a.Postal_Code,
        o.Name AS Owner_Name,
        o.Phone AS Owner_Phone
    FROM Property p
    JOIN Address a ON p.Address_ID = a.Address_ID
    JOIN Owner o ON p.Owner_ID = o.Owner_ID
    WHERE p.Status = 'Available'
        AND (p_property_type IS NULL OR p.Type = p_property_type)
        AND (p_min_price IS NULL OR p.Price >= p_min_price)
        AND (p_max_price IS NULL OR p.Price <= p_max_price)
        AND (p_city IS NULL OR a.City = p_city)
    ORDER BY p.Price ASC;
END //
DELIMITER ;

-- Stored Procedure 2: sp_generate_agent_performance_report
DELIMITER //
CREATE PROCEDURE sp_generate_agent_performance_report(
    IN p_agent_id INT,
    IN p_start_date DATE,
    IN p_end_date DATE
)
BEGIN
    CREATE TEMPORARY TABLE IF NOT EXISTS AgentPerformance (
        Agent_ID INT,
        Agent_Email VARCHAR(100),
        Agency_Name VARCHAR(100),
        Total_Listings INT,
        Active_Listings INT,
        Closed_Listings INT,
        Total_Appointments INT,
        Properties_Sold INT,
        Total_Sales_Value DECIMAL(15, 2),
        Avg_Property_Price DECIMAL(15, 2),
        Performance_Score DECIMAL(5, 2)
    );

    TRUNCATE TABLE AgentPerformance;

    IF p_agent_id IS NULL THEN
        INSERT INTO AgentPerformance
        SELECT
             a.Agent_ID,
             a.Email,
             a.Agency_Name,
             COUNT(DISTINCT l.Listing_ID) AS Total_Listings,
             SUM(CASE WHEN l.Status = 'Active' THEN 1 ELSE 0 END) AS Active_Listings,
             SUM(CASE WHEN l.Status = 'Closed' THEN 1 ELSE 0 END) AS Closed_Listings,
             COUNT(DISTINCT ap.Appointment_ID) AS Total_Appointments,
             COUNT(DISTINCT CASE WHEN p.Status = 'Sold' THEN p.Property_ID END) AS Properties_Sold,
             COALESCE(SUM(CASE WHEN p.Status = 'Sold' THEN p.Price END), 0) AS Total_Sales_Value,
             COALESCE(AVG(CASE WHEN p.Status = 'Sold' THEN p.Price END), 0) AS Avg_Property_Price,
             (COUNT(DISTINCT CASE WHEN p.Status = 'Sold' THEN p.Property_ID END) * 10 +
              COUNT(DISTINCT ap.Appointment_ID) * 2 +
              SUM(CASE WHEN l.Status = 'Closed' THEN 1 ELSE 0 END) * 5) /
              NULLIF(COUNT(DISTINCT l.Listing_ID), 0) AS Performance_Score
        FROM Agent a
        LEFT JOIN Listing l ON a.Agent_ID = l.Agent_ID
             AND l.Listing_Date BETWEEN p_start_date AND p_end_date
        LEFT JOIN Property p ON l.Property_ID = p.Property_ID
        LEFT JOIN Appointment ap ON a.Agent_ID = ap.Agent_ID
             AND ap.Appointment_Date BETWEEN p_start_date AND p_end_date
        GROUP BY a.Agent_ID, a.Email, a.Agency_Name
        ORDER BY Performance_Score DESC;
    ELSE
        INSERT INTO AgentPerformance
        SELECT
             a.Agent_ID,
             a.Email,
             a.Agency_Name,
             COUNT(DISTINCT l.Listing_ID) AS Total_Listings,
             SUM(CASE WHEN l.Status = 'Active' THEN 1 ELSE 0 END) AS Active_Listings,
             SUM(CASE WHEN l.Status = 'Closed' THEN 1 ELSE 0 END) AS Closed_Listings,
             COUNT(DISTINCT ap.Appointment_ID) AS Total_Appointments,
             COUNT(DISTINCT CASE WHEN p.Status = 'Sold' THEN p.Property_ID END) AS Properties_Sold,
             COALESCE(SUM(CASE WHEN p.Status = 'Sold' THEN p.Price END), 0) AS Total_Sales_Value,
             COALESCE(AVG(CASE WHEN p.Status = 'Sold' THEN p.Price END), 0) AS Avg_Property_Price,
             (COUNT(DISTINCT CASE WHEN p.Status = 'Sold' THEN p.Property_ID END) * 10 +
              COUNT(DISTINCT ap.Appointment_ID) * 2 +
              SUM(CASE WHEN l.Status = 'Closed' THEN 1 ELSE 0 END) * 5) /
              NULLIF(COUNT(DISTINCT l.Listing_ID), 0) AS Performance_Score
        FROM Agent a
        LEFT JOIN Listing l ON a.Agent_ID = l.Agent_ID
             AND l.Listing_Date BETWEEN p_start_date AND p_end_date
        LEFT JOIN Property p ON l.Property_ID = p.Property_ID
        LEFT JOIN Appointment ap ON a.Agent_ID = ap.Agent_ID
             AND ap.Appointment_Date BETWEEN p_start_date AND p_end_date
        WHERE a.Agent_ID = p_agent_id
        GROUP BY a.Agent_ID, a.Email, a.Agency_Name;
    END IF;

    SELECT * FROM AgentPerformance;
    DROP TEMPORARY TABLE IF EXISTS AgentPerformance;
END //
DELIMITER ;

-- Stored Procedure 3: GetAvailablePropertiesByCity
DELIMITER //
CREATE PROCEDURE GetAvailablePropertiesByCity(
    IN input_city VARCHAR(100)
)
BEGIN
    SELECT
        p.Property_ID,
        p.Type,
        p.Size,
        p.Price,
        p.Status,
        a.City,
        a.Postal_Code,
        o.Name AS Owner_Name,
        o.Phone AS Owner_Phone,
        o.Email AS Owner_Email
    FROM Property p
    INNER JOIN Address a ON p.Address_ID = a.Address_ID
    INNER JOIN Owner o ON p.Owner_ID = o.Owner_ID
    WHERE a.City = input_city
      AND p.Status = 'Available'
    ORDER BY p.Price ASC;
END //
DELIMITER ;

-- Stored Procedure 4: sp_schedule_appointment
DELIMITER //
CREATE PROCEDURE sp_schedule_appointment(
    IN p_customer_id INT,
    IN p_agent_id INT,
    IN p_date DATE,
    IN p_time TIME
)
BEGIN
    DECLARE next_id INT;

    -- Find the next available Appointment_ID
    SELECT IFNULL(MAX(Appointment_ID), 0) + 1 INTO next_id FROM Appointment;

    -- Insert the new appointment
    INSERT INTO Appointment (
        Appointment_ID,
        Appointment_Date,
        Appointment_Time,
        Customer_ID,
        Agent_ID
    )
    VALUES (
        next_id,
        p_date,
        p_time,
        p_customer_id,
        p_agent_id
    );
END //
DELIMITER ;
