package ai.prodigies.predicta_api.model;

import org.junit.jupiter.api.Test;
import org.springframework.boot.test.autoconfigure.data.mongo.DataMongoTest;
import org.springframework.test.context.ActiveProfiles;

import static org.assertj.core.api.Assertions.assertThat;

@DataMongoTest
@ActiveProfiles("test")
public class CustomerFeedbackValidationTest {

    private static final int MAX_NAME_LENGTH = 100;
    private static final int MAX_FEEDBACK_LENGTH = 1000;

    private boolean validateCustomerFeedback(CustomerFeedback feedback) {
        if (feedback.getCustomerName() == null || feedback.getCustomerName().isBlank()) {
            return false;
        }
        if (feedback.getCustomerName().length() > MAX_NAME_LENGTH) {
            return false;
        }
        if (feedback.getFeedback() == null || feedback.getFeedback().isBlank()) {
            return false;
        }
        if (feedback.getFeedback().length() > MAX_FEEDBACK_LENGTH) {
            return false;
        }
        return true;
    }

    @Test
    void shouldFailValidationWhenCustomerNameIsBlank() {
        // Arrange
        CustomerFeedback feedback = CustomerFeedback.builder()
                .feedback("This is valid feedback")
                .sentiment("Positive")
                .build();

        // Act
        boolean isValid = validateCustomerFeedback(feedback);

        // Assert
        assertThat(isValid).isFalse();
    }

    @Test
    void shouldFailValidationWhenFeedbackIsBlank() {
        // Arrange
        CustomerFeedback feedback = CustomerFeedback.builder()
                .customerName("John Doe")
                .sentiment("Positive")
                .build();

        // Act
        boolean isValid = validateCustomerFeedback(feedback);

        // Assert
        assertThat(isValid).isFalse();
    }

    @Test
    void shouldFailValidationWhenCustomerNameExceedsMaxLength() {
        // Arrange
        String longName = "a".repeat(101); // 101 characters
        CustomerFeedback feedback = CustomerFeedback.builder()
                .customerName(longName)
                .feedback("Valid feedback")
                .sentiment("Positive")
                .build();

        // Act
        boolean isValid = validateCustomerFeedback(feedback);

        // Assert
        assertThat(isValid).isFalse();
    }

    @Test
    void shouldFailValidationWhenFeedbackExceedsMaxLength() {
        // Arrange
        String longFeedback = "a".repeat(1001); // 1001 characters
        CustomerFeedback feedback = CustomerFeedback.builder()
                .customerName("John Doe")
                .feedback(longFeedback)
                .sentiment("Positive")
                .build();

        // Act
        boolean isValid = validateCustomerFeedback(feedback);

        // Assert
        assertThat(isValid).isFalse();
    }

    @Test
    void shouldPassValidationWhenAllFieldsAreValid() {
        // Arrange
        CustomerFeedback feedback = CustomerFeedback.builder()
                .customerName("John Doe")
                .feedback("This is valid feedback")
                .sentiment("Positive")
                .build();

        // Act
        boolean isValid = validateCustomerFeedback(feedback);

        // Assert
        assertThat(isValid).isTrue();
    }
}