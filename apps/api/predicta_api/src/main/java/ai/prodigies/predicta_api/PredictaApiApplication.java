package ai.prodigies.predicta_api;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.boot.autoconfigure.jdbc.DataSourceAutoConfiguration;
import org.springframework.boot.autoconfigure.jdbc.DataSourceTransactionManagerAutoConfiguration;
//@SpringBootApplication(scanBasePackages = "ai.prodigies.predicta_api")
@SpringBootApplication(exclude = {DataSourceAutoConfiguration.class, DataSourceTransactionManagerAutoConfiguration.class})

public class PredictaApiApplication {

	public static void main(String[] args) {
		SpringApplication.run(PredictaApiApplication.class, args);
		System.out.println("Predicta API is up and running!");
	}
}