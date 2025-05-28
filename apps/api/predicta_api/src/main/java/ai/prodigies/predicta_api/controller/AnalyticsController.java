package ai.prodigies.predicta_api.controller;

import ai.prodigies.predicta_api.model.CustomerFeedback;
import ai.prodigies.predicta_api.service.CustomerFeedbackService;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/api/analytics")
public class AnalyticsController {

    private final CustomerFeedbackService customerFeedbackService;

    public AnalyticsController(CustomerFeedbackService customerFeedbackService) {
        this.customerFeedbackService = customerFeedbackService;
    }

    @GetMapping("/feedback/customer/{customerName}")
    public List<CustomerFeedback> getFeedbackByCustomerName(@PathVariable String customerName) {
        return customerFeedbackService.getFeedbackByCustomerName(customerName);
    }

    @GetMapping("/feedback/sentiment/{sentiment}")
    public List<CustomerFeedback> getFeedbackBySentiment(@PathVariable String sentiment) {
        return customerFeedbackService.getFeedbackBySentiment(sentiment);
    }

    @GetMapping("/feedback/search")
    public List<CustomerFeedback> searchFeedback(@RequestParam String keyword) {
        return customerFeedbackService.searchFeedback(keyword);
    }

    @GetMapping("/health")
    public String checkHealth() {
        return "API is running!";
    }
}