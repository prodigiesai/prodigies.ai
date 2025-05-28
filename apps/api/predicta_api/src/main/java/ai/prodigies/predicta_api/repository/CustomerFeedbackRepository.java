package ai.prodigies.predicta_api.repository;

import ai.prodigies.predicta_api.model.CustomerFeedback;
import org.springframework.data.mongodb.repository.MongoRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository // Marks this interface as a Spring-managed repository
public interface CustomerFeedbackRepository extends MongoRepository<CustomerFeedback, String> {

    // Custom query to find feedback by customer name (case-insensitive)
    List<CustomerFeedback> findByCustomerNameIgnoreCase(String customerName);

    // Custom query to find feedback by sentiment
    List<CustomerFeedback> findBySentiment(String sentiment);

    // Custom query to search feedback containing specific keywords
    List<CustomerFeedback> findByFeedbackContainingIgnoreCase(String keyword);

}