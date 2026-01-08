-- Add payment columns to booking table
ALTER TABLE booking 
ADD COLUMN payment_status VARCHAR(20) DEFAULT 'pending',
ADD COLUMN payment_id VARCHAR(100),
ADD COLUMN transaction_id VARCHAR(100),
ADD COLUMN payment_date DATETIME,
ADD COLUMN amount_paid DECIMAL(10,2);

-- Create payments table for detailed payment tracking
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    payment_id VARCHAR(100) NOT NULL,
    payer_id VARCHAR(100),
    payer_email VARCHAR(150),
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(10) DEFAULT 'USD',
    payment_status VARCHAR(50),
    payment_method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES booking(id) ON DELETE CASCADE
);


