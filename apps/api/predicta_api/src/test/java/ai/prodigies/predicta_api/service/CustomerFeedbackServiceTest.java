package ai.prodigies.predicta_api.service;

import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.springframework.boot.test.autoconfigure.data.mongo.DataMongoTest;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Import;
import org.springframework.test.context.ActiveProfiles;
import org.springframework.boot.test.context.TestConfiguration;
import org.springframework.beans.factory.annotation.Autowired;
import ai.prodigies.predicta_api.repository.CustomerFeedbackRepository;
import ai.prodigies.predicta_api.model.CustomerFeedback;

import java.util.List;

import static org.assertj.core.api.Assertions.assertThat;

@DataMongoTest
@ActiveProfiles("test")
@Import(CustomerFeedbackServiceTest.TestConfig.class) // Import T
public class CustomerFeedbackServiceTest {


    @Autowired
    private CustomerFeedbackRepository repository;

    @Autowired
    private CustomerFeedbackService service;

    @TestConfiguration
    static class TestConfig {
        @Bean
        public CustomerFeedbackService customerFeedbackService(CustomerFeedbackRepository repository) {
            return new CustomerFeedbackService(repository);
        }
    }

    @BeforeEach
    void cleanDatabase() {
        repository.deleteAll();
    }
    @Test
    public void shouldReturnFeedbackByCustomerName() {
        CustomerFeedback feedback = new CustomerFeedback();
//        feedback.setId("1L");
        feedback.setCustomerName("John Doe");
        feedback.setFeedback("Great product!");
        feedback.setSentiment("positive");

        repository.save(feedback);

        // Check if the data exists in the repository
        List<CustomerFeedback> allFeedback = repository.findAll();
        System.out.println("Data in repository: " + allFeedback);

        List<CustomerFeedback> result = service.getFeedbackByCustomerName("John Doe");

        assertThat(result).hasSize(1);
        assertThat(result.get(0).getCustomerName()).isEqualTo("John Doe");
    }

    @Test
    void shouldReturnFeedbackBySentiment() {
        // Arrange
        CustomerFeedback feedback = new CustomerFeedback();
        feedback.setCustomerName("Jane Doe");
        feedback.setFeedback("Amazing service!");
        feedback.setSentiment("positive");

        repository.save(feedback); // Save to the actual database

        // Act
        List<CustomerFeedback> result = service.getFeedbackBySentiment("positive");

        // Assert
        assertThat(result).hasSize(1);
        assertThat(result.get(0).getSentiment()).isEqualTo("positive");
    }
}