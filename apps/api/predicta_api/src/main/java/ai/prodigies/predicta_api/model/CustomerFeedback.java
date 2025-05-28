package ai.prodigies.predicta_api.model;

import org.springframework.data.annotation.Id;
import org.springframework.data.mongodb.core.mapping.Document;
import org.springframework.validation.annotation.Validated;

import lombok.Data;
import lombok.NoArgsConstructor;
import lombok.AllArgsConstructor;
import lombok.Builder;

@Document(collection = "CustomerFeedback") // Maps this class to MongoDB collection
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
@Validated // Enables validation on this model
public class CustomerFeedback {

    @Id // MongoDB-specific ID annotation
    private String id; // Use String for MongoDB ObjectId

    private String customerName;

    private String feedback;

    private String sentiment;

    // Custom validation methods
    public void validateCustomerName() {
        if (customerName == null || customerName.isBlank()) {
            throw new IllegalArgumentException("Customer name is mandatory");
        }
        if (customerName.length() > 100) {
            throw new IllegalArgumentException("Customer name should not exceed 100 characters");
        }
    }

    public void validateFeedback() {
        if (feedback == null || feedback.isBlank()) {
            throw new IllegalArgumentException("Feedback is mandatory");
        }
        if (feedback.length() > 1000) {
            throw new IllegalArgumentException("Feedback should not exceed 1000 characters");
        }
    }

    public void validate() {
        validateCustomerName();
        validateFeedback();
    }
}