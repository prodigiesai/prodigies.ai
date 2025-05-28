package ai.prodigies.predicta_api.service;

 import ai.prodigies.predicta_api.model.CustomerFeedback;
 import ai.prodigies.predicta_api.repository.CustomerFeedbackRepository;
 import org.springframework.stereotype.Service;

import java.util.List;
@Service
public class CustomerFeedbackService {

    private final CustomerFeedbackRepository repository;

    public CustomerFeedbackService(CustomerFeedbackRepository repository) {
        this.repository = repository;
    }

    public List<CustomerFeedback> getFeedbackByCustomerName(String customerName) {
        return repository.findByCustomerNameIgnoreCase(customerName);
//        List<CustomerFeedback> feedbackList = repository.findByCustomerNameIgnoreCase(customerName);
//        System.out.println("Query result: " + feedbackList); // Debug output
//        return feedbackList;
    }

    public List<CustomerFeedback> getFeedbackBySentiment(String sentiment) {
        return repository.findBySentiment(sentiment);
    }

    public List<CustomerFeedback> searchFeedback(String keyword) {
        return repository.findByFeedbackContainingIgnoreCase(keyword);
    }
}