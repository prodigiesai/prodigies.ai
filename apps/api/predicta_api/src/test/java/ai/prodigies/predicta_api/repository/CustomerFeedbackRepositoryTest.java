package ai.prodigies.predicta_api.repository;


import ai.prodigies.predicta_api.model.CustomerFeedback;
import ai.prodigies.predicta_api.repository.CustomerFeedbackRepository;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.data.mongo.DataMongoTest;
import org.springframework.test.context.ActiveProfiles;

import java.util.List;

import static org.junit.jupiter.api.Assertions.assertNotNull;

@DataMongoTest
@ActiveProfiles("test")
public class CustomerFeedbackRepositoryTest {

    @Autowired
    private CustomerFeedbackRepository customerFeedbackRepository;

    @BeforeEach
    void cleanDatabase() {
        customerFeedbackRepository.deleteAll();
    }

    @Test
    void contextLoads() {
        assertNotNull(customerFeedbackRepository);
    }


    @Test
    public void testFindByCustomerName() {
        CustomerFeedback feedback = new CustomerFeedback();
        feedback.setCustomerName("John Doe");
        feedback.setFeedback("Great service!");
        feedback.setSentiment("Positive");

        customerFeedbackRepository.save(feedback);

        List<CustomerFeedback> feedbackList = customerFeedbackRepository.findByCustomerNameIgnoreCase("John Doe");
        assertNotNull(feedbackList);
        feedbackList.forEach(System.out::println);
    }

    @Test
    public void testFindBySentiment() {
        // Agregar datos de prueba
        CustomerFeedback feedback = new CustomerFeedback();
        feedback.setCustomerName("Jane Doe");
        feedback.setFeedback("Awesome product!");
        feedback.setSentiment("Positive");
        customerFeedbackRepository.save(feedback);

        List<CustomerFeedback> feedbackList = customerFeedbackRepository.findBySentiment("Positive");
        assertNotNull(feedbackList);
        feedbackList.forEach(System.out::println);
    }

    @Test
    public void testSearchFeedbackByKeyword() {
        // Agregar datos de prueba
        CustomerFeedback feedback = new CustomerFeedback();
        feedback.setCustomerName("Jane Doe");
        feedback.setFeedback("Excellent customer service");
        feedback.setSentiment("Neutral");
        customerFeedbackRepository.save(feedback);

        List<CustomerFeedback> feedbackList = customerFeedbackRepository.findByFeedbackContainingIgnoreCase("service");
        assertNotNull(feedbackList);
        feedbackList.forEach(System.out::println);
    }
}